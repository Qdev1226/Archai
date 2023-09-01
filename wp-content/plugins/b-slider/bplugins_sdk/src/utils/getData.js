async function getData(url = "") {
  let headers = {
    "Content-Type": "application/json",
  };

  const response = await fetch(url, {
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers,
    redirect: "follow",
    referrerPolicy: "no-referrer",
  });
  return response.json();
}

export default getData;
