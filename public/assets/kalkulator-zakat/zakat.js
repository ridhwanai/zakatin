(function () {
const root = document.getElementById("zakatProjectCalculator");

if (!root) {
  // Kalkulator tidak dirender (mis. user belum login / belum punya project).
  return;
}

const projectDataEl = document.getElementById("calculatorProjectData");

if (!projectDataEl) {
  console.warn("Kalkulator zakat: data project tidak ditemukan.");
  return;
}

let projects = [];

try {
  const parsed = JSON.parse(projectDataEl.textContent || "[]");
  if (Array.isArray(parsed)) {
    projects = parsed;
  }
} catch (error) {
  console.warn("Kalkulator zakat: gagal parse JSON project.", error);
}

if (projects.length === 0) {
  console.warn("Kalkulator zakat: project kosong.");
  return;
}

const PARAM_STORAGE_KEY = "zakatinSimulationParamsV1";

const els = {
  projectSelect: document.getElementById("projectSelect"),
  selectedProjectTitle: document.getElementById("selectedProjectTitle"),
  selectedProjectMeta: document.getElementById("selectedProjectMeta"),
  selectedProjectStatus: document.getElementById("selectedProjectStatus"),
  selectedProjectLink: document.getElementById("selectedProjectLink"),
  summaryListCount: document.getElementById("summaryListCount"),
  summaryListBreakdown: document.getElementById("summaryListBreakdown"),
  summaryPeopleCount: document.getElementById("summaryPeopleCount"),
  summaryPeopleBreakdown: document.getElementById("summaryPeopleBreakdown"),
  summaryRiceKg: document.getElementById("summaryRiceKg"),
  summaryFitrahMoney: document.getElementById("summaryFitrahMoney"),
  summaryWajibMoney: document.getElementById("summaryWajibMoney"),
  summaryMalMoney: document.getElementById("summaryMalMoney"),
  ricePricePerKg: document.getElementById("ricePricePerKg"),
  fitrahRecipients: document.getElementById("fitrahRecipients"),
  malRecipients: document.getElementById("malRecipients"),
  combinedRecipients: document.getElementById("combinedRecipients"),
  saveCalculatorParams: document.getElementById("saveCalculatorParams"),
  resetCalculatorParams: document.getElementById("resetCalculatorParams"),
  simulationParamStatus: document.getElementById("simulationParamStatus"),
  resultFitrahMoney: document.getElementById("resultFitrahMoney"),
  resultRicePrice: document.getElementById("resultRicePrice"),
  resultFitrahMoneyToRice: document.getElementById("resultFitrahMoneyToRice"),
  resultFitrahCombinedRice: document.getElementById("resultFitrahCombinedRice"),
  resultFitrahRecipients: document.getElementById("resultFitrahRecipients"),
  resultFitrahTotalRiceForShare: document.getElementById("resultFitrahTotalRiceForShare"),
  resultFitrahRicePerRecipient: document.getElementById("resultFitrahRicePerRecipient"),
  resultFitrahMoneyPerRecipient: document.getElementById("resultFitrahMoneyPerRecipient"),
  resultFitrahMoneyRicePerRecipient: document.getElementById("resultFitrahMoneyRicePerRecipient"),
  resultFitrahCombinedRicePerRecipient: document.getElementById("resultFitrahCombinedRicePerRecipient"),
  resultMalRecipients: document.getElementById("resultMalRecipients"),
  resultMalPerRecipientMoney: document.getElementById("resultMalPerRecipientMoney"),
  resultMalPerRecipientRice: document.getElementById("resultMalPerRecipientRice"),
  resultCombinedTotalMoney: document.getElementById("resultCombinedTotalMoney"),
  resultCombinedRecipients: document.getElementById("resultCombinedRecipients"),
  resultCombinedPerRecipientMoney: document.getElementById("resultCombinedPerRecipientMoney"),
  resultCombinedPerRecipientRice: document.getElementById("resultCombinedPerRecipientRice"),
};

const hasRequiredDom = Object.values(els).every(Boolean);

if (!hasRequiredDom) {
  console.warn("Kalkulator zakat: elemen DOM tidak lengkap.");
  return;
}

const currencyFormatter = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});

const integerFormatter = new Intl.NumberFormat("id-ID", {
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});

const kgFormatter = new Intl.NumberFormat("id-ID", {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2,
});

function toNumber(value) {
  const parsed = Number.parseFloat(String(value ?? "").replace(",", "."));
  if (!Number.isFinite(parsed) || parsed < 0) {
    return 0;
  }
  return parsed;
}

function toPositiveInt(value, fallback = 1) {
  const parsed = Number.parseInt(value, 10);
  if (!Number.isFinite(parsed) || parsed < 1) {
    return fallback;
  }
  return parsed;
}

function formatCurrency(value) {
  return currencyFormatter.format(Math.max(0, value));
}

function formatInteger(value) {
  return integerFormatter.format(Math.max(0, value));
}

function formatKg(value) {
  return `${kgFormatter.format(Math.max(0, value))} Kg`;
}

function setParamStatus(message, variant = "muted") {
  const classByVariant = {
    muted: "text-muted",
    success: "text-success",
    danger: "text-danger",
  };

  const resolvedClass = classByVariant[variant] || classByVariant.muted;
  els.simulationParamStatus.className = `small mb-0 mt-3 ${resolvedClass}`;
  els.simulationParamStatus.textContent = message;
}

function readParamStore() {
  try {
    const raw = window.localStorage.getItem(PARAM_STORAGE_KEY);
    if (!raw) {
      return {};
    }

    const parsed = JSON.parse(raw);
    return parsed && typeof parsed === "object" ? parsed : {};
  } catch {
    return {};
  }
}

function writeParamStore(store) {
  try {
    window.localStorage.setItem(PARAM_STORAGE_KEY, JSON.stringify(store));
    return true;
  } catch {
    return false;
  }
}

function getCurrentParams() {
  return {
    ricePricePerKg: Math.round(Math.max(1, toNumber(els.ricePricePerKg.value))),
    fitrahRecipients: toPositiveInt(els.fitrahRecipients.value, 8),
    malRecipients: toPositiveInt(els.malRecipients.value, 8),
    combinedRecipients: toPositiveInt(els.combinedRecipients.value, 8),
  };
}

function applyParamsToInputs(params) {
  els.ricePricePerKg.value = String(Math.round(Math.max(1, toNumber(params.ricePricePerKg))));
  els.fitrahRecipients.value = String(toPositiveInt(params.fitrahRecipients, 8));
  els.malRecipients.value = String(toPositiveInt(params.malRecipients, 8));
  els.combinedRecipients.value = String(toPositiveInt(params.combinedRecipients, 8));
}

function getSavedParams(projectId) {
  const store = readParamStore();
  const raw = store[String(projectId)];

  if (!raw || typeof raw !== "object") {
    return null;
  }

  return {
    ricePricePerKg: Math.round(Math.max(1, toNumber(raw.ricePricePerKg))),
    fitrahRecipients: toPositiveInt(raw.fitrahRecipients, 8),
    malRecipients: toPositiveInt(raw.malRecipients, 8),
    combinedRecipients: toPositiveInt(raw.combinedRecipients, 8),
  };
}

function normalizeSummary(summary) {
  return {
    total_list_count: toNumber(summary?.total_list_count),
    total_list_count_rice: toNumber(summary?.total_list_count_rice),
    total_list_count_money: toNumber(summary?.total_list_count_money),
    total_list_count_custom: toNumber(summary?.total_list_count_custom),
    total_list_count_mal: toNumber(summary?.total_list_count_mal),
    total_people: toNumber(summary?.total_people),
    total_people_rice: toNumber(summary?.total_people_rice),
    total_people_money: toNumber(summary?.total_people_money),
    total_people_custom: toNumber(summary?.total_people_custom),
    total_people_mal: toNumber(summary?.total_people_mal),
    total_rice_kg: toNumber(summary?.total_rice_kg),
    total_fitrah_money: toNumber(summary?.total_fitrah_money),
    total_wajib_money: toNumber(summary?.total_wajib_money),
    total_mal_money: toNumber(summary?.total_mal_money),
  };
}

function getProjectById(projectId) {
  const targetId = toPositiveInt(projectId, 0);
  return projects.find((project) => toPositiveInt(project.id, 0) === targetId) || null;
}

function getSelectedProject() {
  return getProjectById(els.projectSelect.value) || projects[0];
}

function mapStatus(status) {
  const normalized = String(status || "").toLowerCase();
  if (normalized === "active") {
    return "Aktif";
  }
  if (normalized === "draft") {
    return "Draft";
  }
  return normalized ? normalized.charAt(0).toUpperCase() + normalized.slice(1) : "-";
}

function deriveDefaultRicePrice(project) {
  const riceRate = toNumber(project.rice_rate_per_person);
  const moneyRate = toNumber(project.money_rate_per_person);

  if (riceRate > 0 && moneyRate > 0) {
    return Math.max(1, moneyRate / riceRate);
  }

  return 15000;
}

function updateProjectQueryParam(projectId) {
  const url = new URL(window.location.href);
  url.searchParams.set("project", String(projectId));
  window.history.replaceState({}, "", url.toString());
}

function updateProjectHeader(project) {
  const summary = normalizeSummary(project.summary);
  const projectUrlTemplate = root.dataset.projectUrlTemplate || "";

  els.selectedProjectTitle.textContent = project.title || "-";
  els.selectedProjectMeta.textContent = `Tahun ${project.hijri_year || "-"} H`;
  els.selectedProjectStatus.textContent = mapStatus(project.status);

  if (projectUrlTemplate.includes("__PROJECT__")) {
    els.selectedProjectLink.href = projectUrlTemplate.replace("__PROJECT__", String(project.id));
  } else {
    els.selectedProjectLink.href = "#";
  }

  els.summaryListCount.textContent = formatInteger(summary.total_list_count);
  els.summaryListBreakdown.textContent =
    `Beras ${formatInteger(summary.total_list_count_rice)} | ` +
    `Uang ${formatInteger(summary.total_list_count_money)} | ` +
    `Custom ${formatInteger(summary.total_list_count_custom)} | ` +
    `Mal ${formatInteger(summary.total_list_count_mal)}`;

  els.summaryPeopleCount.textContent = formatInteger(summary.total_people);
  els.summaryPeopleBreakdown.textContent =
    `Beras ${formatInteger(summary.total_people_rice)} | ` +
    `Uang ${formatInteger(summary.total_people_money)} | ` +
    `Custom ${formatInteger(summary.total_people_custom)} | ` +
    `Mal ${formatInteger(summary.total_people_mal)}`;

  els.summaryRiceKg.textContent = formatKg(summary.total_rice_kg);
  els.summaryFitrahMoney.textContent = formatCurrency(summary.total_fitrah_money);
  els.summaryWajibMoney.textContent = formatCurrency(summary.total_wajib_money);
  els.summaryMalMoney.textContent = formatCurrency(summary.total_mal_money);
}

function calculateAndRender() {
  const project = getSelectedProject();
  const summary = normalizeSummary(project.summary);

  const fitrahRecipients = toPositiveInt(els.fitrahRecipients.value, 1);
  const malRecipients = toPositiveInt(els.malRecipients.value, 1);
  const combinedRecipients = toPositiveInt(els.combinedRecipients.value, 1);
  const ricePricePerKg = Math.max(1, toNumber(els.ricePricePerKg.value));

  els.fitrahRecipients.value = String(fitrahRecipients);
  els.malRecipients.value = String(malRecipients);
  els.combinedRecipients.value = String(combinedRecipients);
  els.ricePricePerKg.value = String(Math.round(ricePricePerKg));

  const fitrahMoneyToRiceKg = summary.total_fitrah_money / ricePricePerKg;
  const fitrahCombinedRiceKg = summary.total_rice_kg + fitrahMoneyToRiceKg;

  const fitrahRicePerRecipient = summary.total_rice_kg / fitrahRecipients;
  const fitrahMoneyPerRecipient = summary.total_fitrah_money / fitrahRecipients;
  const fitrahMoneyRicePerRecipient = fitrahMoneyToRiceKg / fitrahRecipients;
  const fitrahCombinedRicePerRecipient = fitrahCombinedRiceKg / fitrahRecipients;

  const malPerRecipientMoney = summary.total_mal_money / malRecipients;
  const malPerRecipientRice = malPerRecipientMoney / ricePricePerKg;

  const totalCombinedMoney =
    summary.total_fitrah_money + summary.total_wajib_money + summary.total_mal_money;
  const combinedPerRecipientMoney = totalCombinedMoney / combinedRecipients;
  const combinedPerRecipientRice = combinedPerRecipientMoney / ricePricePerKg;

  els.resultFitrahMoney.textContent = formatCurrency(summary.total_fitrah_money);
  els.resultRicePrice.textContent = formatCurrency(ricePricePerKg);
  els.resultFitrahMoneyToRice.textContent = formatKg(fitrahMoneyToRiceKg);
  els.resultFitrahCombinedRice.textContent = formatKg(fitrahCombinedRiceKg);

  els.resultFitrahRecipients.textContent = `${formatInteger(fitrahRecipients)} Orang`;
  els.resultFitrahTotalRiceForShare.textContent = formatKg(fitrahCombinedRiceKg);
  els.resultFitrahRicePerRecipient.textContent = formatKg(fitrahRicePerRecipient);
  els.resultFitrahMoneyPerRecipient.textContent = formatCurrency(fitrahMoneyPerRecipient);
  els.resultFitrahMoneyRicePerRecipient.textContent = formatKg(fitrahMoneyRicePerRecipient);
  els.resultFitrahCombinedRicePerRecipient.textContent = formatKg(fitrahCombinedRicePerRecipient);

  els.resultMalRecipients.textContent = `${formatInteger(malRecipients)} Orang`;
  els.resultMalPerRecipientMoney.textContent = formatCurrency(malPerRecipientMoney);
  els.resultMalPerRecipientRice.textContent = formatKg(malPerRecipientRice);

  els.resultCombinedTotalMoney.textContent = formatCurrency(totalCombinedMoney);
  els.resultCombinedRecipients.textContent = `${formatInteger(combinedRecipients)} Orang`;
  els.resultCombinedPerRecipientMoney.textContent = formatCurrency(combinedPerRecipientMoney);
  els.resultCombinedPerRecipientRice.textContent = formatKg(combinedPerRecipientRice);
}

function applyProject(projectId, resetRicePrice = false) {
  const project = getProjectById(projectId) || projects[0];

  if (!project) {
    return;
  }

  els.projectSelect.value = String(project.id);
  updateProjectHeader(project);
  updateProjectQueryParam(project.id);

  if (resetRicePrice) {
    const savedParams = getSavedParams(project.id);

    if (savedParams) {
      applyParamsToInputs(savedParams);
      setParamStatus("Parameter tersimpan dimuat untuk project ini.", "success");
    } else {
      applyParamsToInputs({
        ricePricePerKg: Math.round(deriveDefaultRicePrice(project)),
        fitrahRecipients: 8,
        malRecipients: 8,
        combinedRecipients: 8,
      });
      setParamStatus("Belum ada parameter tersimpan untuk project ini.", "muted");
    }
  }

  calculateAndRender();
}

function resetParamsToDefault() {
  const project = getSelectedProject();
  applyParamsToInputs({
    ricePricePerKg: Math.round(deriveDefaultRicePrice(project)),
    fitrahRecipients: 8,
    malRecipients: 8,
    combinedRecipients: 8,
  });
  setParamStatus("Parameter direset ke default. Klik Simpan jika ingin menyimpan default ini.", "muted");
  calculateAndRender();
}

function saveParamsForCurrentProject() {
  const project = getSelectedProject();
  calculateAndRender();

  const store = readParamStore();
  store[String(project.id)] = getCurrentParams();

  const isSaved = writeParamStore(store);
  if (isSaved) {
    setParamStatus("Parameter simulasi berhasil disimpan untuk project ini.", "success");
  } else {
    setParamStatus("Gagal menyimpan parameter. Cek izin local storage browser.", "danger");
  }
}

function init() {
  const selectedFromData = toPositiveInt(root.dataset.selectedProjectId, 0);
  const selectedProject = getProjectById(selectedFromData) || projects[0];

  els.projectSelect.addEventListener("change", () => {
    applyProject(els.projectSelect.value, true);
  });

  [els.ricePricePerKg, els.fitrahRecipients, els.malRecipients, els.combinedRecipients].forEach(
    (input) => {
      input.addEventListener("input", () => {
        calculateAndRender();
        setParamStatus("Perubahan parameter belum disimpan.", "muted");
      });
    }
  );

  els.saveCalculatorParams.addEventListener("click", saveParamsForCurrentProject);
  els.resetCalculatorParams.addEventListener("click", resetParamsToDefault);

  applyProject(selectedProject.id, true);
}

init();
})();
