async function postData(url = "", data = {}) {
  let body = JSON.stringify(data);
  let headers = {
    "Content-Type": "application/json",
  };
  if (url.includes("ajax.php")) {
    const formData = new FormData();
    Object.keys(data).map((key) => formData.append(key, data[key]));
    body = formData;
    headers = {};
  }

  const response = await fetch(url, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers,
    redirect: "follow",
    referrerPolicy: "no-referrer",
    body,
  });
  return response.json();
}

export default postData;
