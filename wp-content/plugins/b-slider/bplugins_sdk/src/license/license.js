/* eslint-disable no-console */
// import { render } from "react-dom";
import scramble from "./../utils/scramble";
import "./license.scss";
// import LicenseModal from "../components/License/LicenseModal";

// document.addEventListener("DOMContentLoaded", function () {
//   const dom = document.getElementById(`${prefix}_license_popup`);
//   const dataset = { ...dom.dataset } || {};
//   Object.keys(dom.dataset).map((key) => delete dom.dataset[key]);

//   render(<LicenseModal />, dom);
// });

class LicenseHandler {
  constructor(prefix, permalinks) {
    this.prefix = prefix;
    this.products = permalinks;
    this.info = window[`${this.prefix}License`] || window[`${this.prefix}Layer`];
  }

  initialize() {
    const modal = document.querySelector(`.${this.prefix}_license_popup .popupWrapper`);
    if (!modal) {
      console.warn("modal undefined");
      return;
    }

    this.syncLicense();

    this.endpoint = "https://api.bplugins.com/wp-json/license/v1/gumroad";
    this.modal = modal;

    this.agreed = false;

    // elements
    const opener = document.querySelector(`.${this.prefix}_modal_opener`);
    const closer = modal.querySelector(".closer");
    const agree = modal.querySelector(".agree");
    const activateBtn = modal.querySelector(".btn-activate");
    const deactivateBtn = modal.querySelector(".btn-deactivate");
    const loader = modal.querySelector(".bpl_loader");
    this.headers = { "content-Type": "application/json" };

    if (!opener) console.error("opener not found");
    if (!closer) console.error("closer not found");
    if (!loader) console.error("loader not found");

    opener?.addEventListener("click", (e) => {
      e.preventDefault();
      modal.style.display = "block";
    });

    closer?.addEventListener("click", (e) => {
      e.preventDefault();
      modal.style.display = "none";
    });

    agree?.addEventListener("click", () => {
      this.agreed = agree?.checked;
      if (activateBtn) activateBtn.disabled = !agree?.checked;
    });

    activateBtn?.addEventListener("click", async (e) => {
      e.preventDefault();
      loader.style.display = "inline-block";
      activateBtn.disabled = true;
      const field = modal.querySelector("input.license_key");
      if (field?.value) {
        const licenseKey = field?.value?.includes("$") ? scramble(field.value, "decode") : field?.value;
        const activate = await this.activeLicense(licenseKey, this.products);
        if (activate) {
          this.setNotice("notice-success", "License key Activated!, Thank you");
        }
        //  else {
        //   this.setNotice("notice-warning", "Something went wrong!");
        // }
      } else {
        this.setNotice("notice-warning", "Please input a license key");
      }

      activateBtn.disabled = false;
      loader.style.display = "none";
    });

    deactivateBtn?.addEventListener("click", async (e) => {
      e.preventDefault();
      loader.style.display = "inline-block";
      deactivateBtn.disabled = true;

      const field = modal.querySelector("input.license_key");

      if (field?.value) {
        const licenseKey = field?.value?.includes("$") ? scramble(field.value, "decode") : field?.value;
        const deactivate = await this.deactiveLicense(licenseKey, this.products);
        if (deactivate) {
          this.setNotice("notice-success", "License key deactivated.");
        }
      }

      deactivateBtn.disabled = false;
      loader.style.display = "none";
    });
  }

  async serverHandler(params) {
    const data = {
      quantity: 1,
      website: window.location?.origin,
      product: this.prefix,
      email: this.info?.email,
      action: "add",
      ...params,
    };

    const response = await fetch(this.endpoint, {
      method: "POST",
      headers: {
        "content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((res) => res.json())
      .then((res) => {
        return res;
      });
    return response;
  }

  async activeLicense(license_key) {
    const { permalink, success, quantity, isAppSumo } = await this.verifyGumroad(license_key);
    if (success) {
      const { active, message } = await this.serverHandler({ license_key, quantity, isAppSumo });
      if (active) {
        const data = new FormData();
        data.append("action", `${this.prefix}_active_license_key`);
        data.append("data", scramble(JSON.stringify({ activated: true, key: license_key, permalink, time: new Date().getTime() + "" })));
        data.append("nonce", this.info?.nonce);
        const activatePlugin = await fetch(this.info?.ajaxURL, {
          method: "POST",
          body: data,
        })
          .then((res) => res.json())
          .then((res) => res);
        return activatePlugin?.success;
      } else {
        this.setNotice("notice-warning", message);
      }
    } else {
      this.setNotice("notice-warning", "Invalid License key");
    }
  }

  async syncLicense() {
    const website = window.location.origin.replace(/(^\w+:|^)\/\//, "").replace("/", "");
    const data = new FormData();
    data.append("action", `${this.prefix}_sync_license_key`);
    data.append("website", website);
    data.append("nonce", this.info?.nonce);

    try {
      await fetch(this.info?.ajaxURL, {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((res) => {});
    } catch (error) {
      console.log(error.message);
    }
  }

  async verifyLicense(license_key) {
    const { permalink, success } = await this.verifyGumroad(license_key);
    const data = new FormData();
    data.append("action", `${this.prefix}_active_license_key`);
    data.append("data", scramble(JSON.stringify({ activated: success, key: license_key, permalink, time: new Date().getTime() + "" })));
    data.append("nonce", this.info?.nonce);

    try {
      await fetch(this.info?.ajaxURL, {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((res) => res);
    } catch (error) {
      console.log(error.message);
    }
  }

  async deactiveLicense(license_key) {
    // const { permalink, success } = await this.verifyGumroad(license_key);

    // console.log({ permalink, success });

    // if (success) {
    const deactivated = await this.serverHandler({ license_key, action: "deactive" });
    if (deactivated) {
      const data = new FormData();
      data.append("action", `${this.prefix}_active_license_key`);
      data.append("data", this.agreed ? "{}" : scramble(JSON.stringify({ activated: false, key: license_key, permalink: "" })));
      data.append("nonce", this.info?.nonce);

      const deactivate = await fetch(this.info?.ajaxURL, {
        method: "POST",
        body: data,
      })
        .then((res) => res.json())
        .then((res) => res);
      return deactivate?.success;
    } else {
      this.setNotice("notice-warning", "something went wrong!");
    }
    // } else {
    //   this.setNotice("notice-warning", "invalid license key!");
    // }
  }

  async verifyGumroad(key) {
    let response = {};
    const licenseKey = key.includes("$") ? scramble(key, "decode") : key;

    const verify = async (data) => {
      let response = {};
      let res = await fetch("https://api.gumroad.com/v2/licenses/verify", {
        method: "POST",
        headers: { "content-Type": "application/json" },
        body: JSON.stringify({ ...data, license_key: licenseKey }),
      })
        .then((res) => res.json())
        .then((res) => res);
      if (res.success) {
        response = res;
      }
      return response;
    };

    if (Array.isArray(this.products)) {
      for (let permalink of this.products) {
        response = await verify({ product_permalink: permalink });
        if (response.success) break;
      }
    } else {
      for (let permalink in this.products) {
        response = await verify({ product_permalink: permalink, product_id: this.products[permalink] });
        if (response.success) break;
      }
    }

    const quantity = this.getQuantity(response);
    const isAppSumo = response?.purchase?.product_name?.includes("Sumo") || false;
    const permalink = response?.purchase?.permalink;
    return { quantity, success: response.success, permalink, isAppSumo };
  }

  setNotice(classes, noticeText) {
    const noticeParent = this.modal.querySelector(".license_notice");
    const notice = document.createElement("div");
    notice.classList = `notice ${classes}`;
    notice.innerText = noticeText;
    noticeParent.appendChild(notice);
    setTimeout(() => {
      notice.remove();
      if (classes == "notice-success") {
        location.reload();
      }
    }, 2000);
    return;
  }

  getQuantity(response) {
    const variants = {
      "(Single Site)": 1,
      "Single Site License": 1,
      "Single Site ": 1,
      "(3 Sites)": 3,
      "3 Sites License": 3,
      "( 3 Sites)": 3,
      "3 Sites": 3,
      "(3 Sites License)": 3,
      "(5 Sites)": 5,
      "5 Sites": 5,
      "(Developer  - Unlimited)": 1000,
      "Developer / Unlimited": 1000,
      "Developer / Unlimited sites": 1000,
      "Developer/Agency license for Unlimited Sites": 1000,
      "Developer/Agency License - Unlimited Site": 1000,
      "(Developer/Agency License - Unlimited Site)": 1000,
      "(Developer)": 1000,
      Agency: 1000,
    };

    let quantity = 1;

    for (let item of ["Developer", "Agency", "Unlimited"]) {
      if (response?.purchase?.variants?.toLowerCase()?.includes(item.toLowerCase())) {
        quantity = 1000;
        break;
      } else {
        quantity = variants[response?.purchase?.variants] || 1;
      }
    }

    return quantity;
  }
}

export default LicenseHandler;

window.LicenseHandler = LicenseHandler;
