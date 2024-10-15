var a = Object.defineProperty;
var p = (e, r, s) =>
  r in e
    ? a(e, r, { enumerable: !0, configurable: !0, writable: !0, value: s })
    : (e[r] = s);
var t = (e, r, s) => (p(e, typeof r != "symbol" ? r + "" : r, s), s);
import { b as i, a as h } from "./bootstrap-B2JubnBk.js";
import { _ as o } from "./currency-gBUix5n2.js";
import { B as n } from "./tax-8FHrRWQm.js";
import "./chart-D2s_fKm5.js";
import "./runtime-core.esm-bundler-K4i56aP4.js";
class m {
  constructor() {
    t(this, "_mysales");
    t(this, "_reports", { mysales: i.get("/api/reports/cashier-report") });
    this._mysales = new n({});
    for (let r in this._reports) this.loadReport(r);
  }
  loadReport(r) {
    return this._reports[r].subscribe((s) => {
      this[`_${r}`].next(s);
    });
  }
  refreshReport() {
    i.get("/api/reports/cashier-report?refresh=true").subscribe((r) => {
      this._mysales.next(r),
        h.success(o("The report has been refreshed."), o("OK")).subscribe();
    });
  }
  get mysales() {
    return this._mysales;
  }
}
window.Cashier = new m();
