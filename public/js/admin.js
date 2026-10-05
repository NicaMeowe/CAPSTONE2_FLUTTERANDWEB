(function () {
    "use strict";

    var data = window.preventiaData || {};
    var routes = window.preventiaRoutes || {};

    function showToast(message, type) {
        var toast = document.createElement("div");
        toast.className = "preventia-toast " + (type || "success");
        toast.textContent = message;
        document.body.appendChild(toast);
        window.setTimeout(function () { toast.classList.add("visible"); }, 10);
        window.setTimeout(function () {
            toast.classList.remove("visible");
            window.setTimeout(function () { toast.remove(); }, 240);
        }, 2800);
    }

    var style = document.createElement("style");
    style.textContent = ".preventia-toast{position:fixed;right:22px;bottom:22px;z-index:40;padding:12px 15px;border:1px solid #dce2ec;border-radius:9px;color:#536078;background:#fff;box-shadow:0 14px 35px rgba(32,43,76,.16);font:11px 'DM Sans',sans-serif;opacity:0;transform:translateY(8px);transition:.25s}.preventia-toast.visible{opacity:1;transform:none}";
    document.head.appendChild(style);

    var menu = document.querySelector("#mobile-menu");
    var sidebar = document.querySelector("#sidebar");
    if (menu && sidebar) {
        menu.addEventListener("click", function () { sidebar.classList.toggle("open"); });
        document.addEventListener("click", function (event) {
            if (window.innerWidth <= 820 && sidebar.classList.contains("open") && !sidebar.contains(event.target) && event.target !== menu) {
                sidebar.classList.remove("open");
            }
        });
    }

    var toggle = document.querySelector("[data-toggle-password]");
    if (toggle) {
        toggle.addEventListener("click", function () {
            var input = document.querySelector("#password");
            if (!input) return;
            var visible = input.type === "text";
            input.type = visible ? "password" : "text";
            toggle.textContent = visible ? "Show" : "Hide";
            toggle.setAttribute("aria-label", visible ? "Show password" : "Hide password");
        });
    }

    document.querySelectorAll("[data-table-search]").forEach(function (input) {
        input.addEventListener("input", function () {
            var table = document.querySelector(input.getAttribute("data-table-search"));
            if (!table) return;
            var query = input.value.toLowerCase().trim();
            table.querySelectorAll("tbody tr").forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().indexOf(query) >= 0 ? "" : "none";
            });
        });
    });

    document.querySelectorAll("[data-table-filter]").forEach(function (select) {
        select.addEventListener("change", function () {
            var table = document.querySelector(select.getAttribute("data-table-filter"));
            if (!table) return;
            var column = Number(select.getAttribute("data-filter-column")) - 1;
            var query = select.value.toLowerCase();
            table.querySelectorAll("tbody tr").forEach(function (row) {
                var cell = row.children[column];
                row.style.display = !query || (cell && cell.textContent.toLowerCase().indexOf(query) >= 0) ? "" : "none";
            });
        });
    });

    var modal = document.querySelector("#detail-modal");
    var activeVisitId = null;
    var activeVisitRow = null;

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    }

    function setButtonBusy(button, busy) {
        if (!button) return;
        button.disabled = busy;
        button.classList.toggle("is-loading", busy);
    }

    function writeRecord(url, payload) {
        return fetch(url, {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(payload)
        }).then(function (response) {
            return response.json().catch(function () { return {}; }).then(function (body) {
                if (!response.ok) throw new Error(body.message || "The update could not be saved.");
                return body;
            });
        });
    }

    function recordUrl(template, id) {
        return (template || "").replace("__ID__", encodeURIComponent(id));
    }

    function updateAlertRow(row, status) {
        if (!row) return;
        var label = row.querySelector("[data-alert-status]");
        if (label) {
            label.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            label.className = "table-status status-label-" + status;
        }
        row.querySelectorAll("[data-record-action='alert-status']").forEach(function (button) {
            button.disabled = status === "resolved";
        });
    }

    function updateVisitRow(row, action) {
        if (!row) return;
        var detail = {};
        try { detail = JSON.parse(row.getAttribute("data-detail") || "{}"); } catch (error) {}
        if (action === "verified") detail.verified = true;
        if (action === "reviewed") detail.status = "Reviewed";
        if (action === "resolved") detail.status = "Resolved";
        row.setAttribute("data-detail", JSON.stringify(detail));
        var status = row.querySelector(".table-status");
        if (status && detail.status) {
            status.textContent = detail.status;
            status.className = "table-status status-label-" + detail.status.toLowerCase().replace(/\s+/g, "-");
        }
        var verification = row.querySelector(".verification");
        if (verification && detail.verified) {
            verification.textContent = "● GPS verified";
            verification.className = "verification verified";
        }
    }

    document.querySelectorAll("[data-row-details]").forEach(function (button) {
        button.addEventListener("click", function () {
            var row = button.closest("tr");
            var detail = {};
            try { detail = JSON.parse(row.getAttribute("data-detail") || "{}"); } catch (error) {}
            activeVisitId = detail.id || null;
            activeVisitRow = row;
            var title = document.querySelector("[data-modal-title]");
            var body = document.querySelector("[data-modal-body]");
            var feedback = document.querySelector("[data-modal-feedback]");
            if (title) title.textContent = detail.facility || "Visit details";
            if (feedback) feedback.textContent = "";
            if (body) {
                body.innerHTML = "";
                [
                    ["Representative", detail.representative],
                    ["Doctor", detail.doctor],
                    ["Status", detail.status],
                    ["Duration", detail.duration],
                    ["GPS coordinates", detail.location],
                    ["Verification", detail.verified ? "GPS verified" : "Needs review"]
                ].forEach(function (item) {
                    var block = document.createElement("div");
                    block.innerHTML = "<span>" + item[0] + "</span><strong>" + (item[1] || "—") + "</strong>";
                    body.appendChild(block);
                });
            }
            document.querySelectorAll("[data-visit-action]").forEach(function (actionButton) {
                actionButton.disabled = false;
                actionButton.setAttribute("data-visit-id", activeVisitId || "");
            });
            if (modal) modal.hidden = false;
        });
    });
    document.querySelectorAll("[data-modal-close]").forEach(function (button) {
        button.addEventListener("click", function () { if (modal) modal.hidden = true; });
    });
    if (modal) modal.addEventListener("click", function (event) { if (event.target === modal) modal.hidden = true; });

    document.querySelectorAll("[data-action]").forEach(function (button) {
        button.addEventListener("click", function () {
            var action = button.getAttribute("data-action");
            if (action === "refresh") {
                button.classList.add("is-loading");
                window.setTimeout(function () { window.location.reload(); }, 350);
                return;
            }
            if (action === "download") { showToast("Report export is ready to connect to your preferred format."); return; }
            if (action === "notify") { showToast("Update composer is ready for the next team message."); return; }
            if (action === "mark-read") {
                var alertButtons = Array.prototype.slice.call(document.querySelectorAll("[data-record-action='alert-status'][data-status='reviewed']"));
                if (!alertButtons.length) {
                    showToast("There are no visible alerts to review.", "error");
                    return;
                }
                alertButtons.forEach(function (alertButton) { setButtonBusy(alertButton, true); });
                Promise.all(alertButtons.map(function (alertButton) {
                    var row = alertButton.closest("[data-alert-row]");
                    return writeRecord(recordUrl(routes.alertStatus, alertButton.getAttribute("data-record-id")), { status: "reviewed" })
                        .then(function () { updateAlertRow(row, "reviewed"); });
                })).then(function () {
                    showToast("All visible alerts marked as reviewed.", "success");
                }).catch(function (error) {
                    showToast(error.message, "error");
                }).finally(function () {
                    alertButtons.forEach(function (alertButton) { setButtonBusy(alertButton, false); });
                });
            }
        });
    });

    document.querySelectorAll("[data-record-action='alert-status']").forEach(function (button) {
        button.addEventListener("click", function () {
            var row = button.closest("[data-alert-row]");
            var status = button.getAttribute("data-status");
            setButtonBusy(button, true);
            writeRecord(recordUrl(routes.alertStatus, button.getAttribute("data-record-id")), { status: status })
                .then(function () {
                    updateAlertRow(row, status);
                    showToast("Alert marked " + status + ".", "success");
                })
                .catch(function (error) {
                    showToast(error.message, "error");
                })
                .finally(function () { setButtonBusy(button, false); });
        });
    });

    document.querySelectorAll("[data-visit-action]").forEach(function (button) {
        button.addEventListener("click", function () {
            if (!activeVisitId) {
                showToast("Select a visit before saving a review action.", "error");
                return;
            }
            var action = button.getAttribute("data-visit-action");
            var feedback = document.querySelector("[data-modal-feedback]");
            setButtonBusy(button, true);
            writeRecord(recordUrl(routes.visitReview, activeVisitId), { action: action })
                .then(function () {
                    updateVisitRow(activeVisitRow, action);
                    if (feedback) {
                        feedback.className = "modal-feedback success";
                        feedback.textContent = "Visit updated in Firebase.";
                    }
                    showToast("Visit marked " + action + ".", "success");
                })
                .catch(function (error) {
                    if (feedback) {
                        feedback.className = "modal-feedback error";
                        feedback.textContent = error.message;
                    }
                    showToast(error.message, "error");
                })
                .finally(function () { setButtonBusy(button, false); });
        });
    });
}());