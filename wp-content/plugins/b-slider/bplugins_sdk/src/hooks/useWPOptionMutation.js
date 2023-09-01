/* eslint-disable no-console */
import { useState } from "@wordpress/element";

const useWPOptionMutation = (key, { type: dataType = "string" }) => {
  const [isLoading, setIsLoading] = useState(false);
  const [isError, setIsError] = useState(false);
  const [error, setError] = useState(null);
  const [data, setData] = useState(null);

  const saveData = (data) => {
    setIsError(false);
    setError(null);
    setIsLoading(true);
    try {
      const model = new wp.api.models.Settings({
        [key]: prepareData(data, "saving"),
      });
      model.save().then((response) => {
        setData(prepareData(response[key], "response"));
        setIsLoading(false);
      });
    } catch (error) {
      setIsError(true);
      setError(error?.message);
      setIsLoading(false);
    }
  };

  const prepareData = (data, type) => {
    let newData = data;
    if (dataType === "object") {
      const { isLoaded, ...restData } = data;
      newData = restData;
      try {
        newData = type === "saving" ? JSON.stringify(data) : JSON.parse(data);
      } catch (error) {
        setError(error?.message);
        setIsError(true);
      }
    }
    return newData;
  };

  return { data, saveData, isLoading, isError, error };
};

export default useWPOptionMutation;
