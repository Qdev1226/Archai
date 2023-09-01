/* eslint-disable no-console */
import { useState, useEffect } from "@wordpress/element";

const useWPOptionQuery = (key) => {
  const [isLoading, setIsLoading] = useState(true);
  const [isError, setIsError] = useState(false);
  const [error, setError] = useState(null);
  const [data, setData] = useState(null);

  useEffect(() => {
    setIsError(false);
    setError(null);
    wp.api.loadPromise.then(() => {
      const settings = new wp.api.models.Settings();
      settings.fetch().then((response) => {
        setData(prepareData(response[key]));
        setIsLoading(false);
      });
    });
  }, []);

  const prepareData = (data) => {
    let newData = data;
    try {
      newData = JSON.parse(data);
    } catch (error) {
      setIsError(true);
      setError(error.message);
    }

    return newData;
  };

  return { data, isLoading, isError, error };
};

export default useWPOptionQuery;
