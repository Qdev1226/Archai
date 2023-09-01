const scramble = (data = "", type = "encode") => {
  const originalKey = "abcdefghijklmnopqrstuvwxyz1234567890 -";
  const key = "z1ntg4ihmwj5cr09byx8spl7ak6vo2q3eduf!$";
  let resultData = "";
  if (type == "encode") {
    if (data != "") {
      const length = data.length;
      for (let i = 0; i < length; i++) {
        const position = originalKey.indexOf(data[i]);
        if (position !== -1) {
          resultData += key[position];
        } else {
          resultData += data[i];
        }
      }
    }
  }

  if (type == "decode") {
    if (data != "") {
      const length = data.length;
      for (let i = 0; i < length; i++) {
        const currentChar = data[i];
        const position = key.indexOf(currentChar);
        if (position !== -1) {
          resultData += originalKey[position];
        } else {
          resultData += currentChar;
        }
      }
    }
  }
  return resultData;
};

export default scramble;
