function extractKeyFromJSON(jsonString) {
  const regex = /"key":"([^"]+)"/;
  const match = regex.exec(jsonString);

  if (match && match.length > 1) {
    return match[1];
  } else {
    return null;
  }
}

export default extractKeyFromJSON;
