document.addEventListener("DOMContentLoaded", function () {
  console.log("loaded");
  const importBtn = document.getElementById("ytp_import_btn");
  importBtn?.addEventListener("click", function (e) {
    e.preventDefault();
    const data = new FormData();
    data.append("action", "ytp_import_data");
    fetch(ytpAdmin?.ajaxUrl, {
      method: "POST",
      body: data,
      credentials: "same-origin",
    })
      .then((res) => {
        console.log({ res });
        return res.json();
      })
      .then((data) => {
        if (data?.success) {
          location.href = location.origin + location.pathname + "?ytp_import=success";
        }
      });
  });
});
