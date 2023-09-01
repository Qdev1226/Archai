import { useEffect } from "@wordpress/element";
import LicenseHandler from "./../../license/license";
import scramble from "../../utils/scramble";
import useWPOptionQuery from "./../../hooks/useWPOptionQuery";

import data from "./../../../config.json";
import extractKeyFromJSON from "../../utils/extractKeyFromJSON";
import rootConfig from "./../../../../bsdk_config.json";
// const rootConfig = {};
const { prefix: sdkPrefix } = data;

const BPLSDK = ({ setAttributes }) => {
  const prefix = rootConfig?.prefix || sdkPrefix;
  const { data } = useWPOptionQuery(`${prefix}_pipe`);

  useEffect(() => {
    if (!prefix) {
      // eslint-disable-next-line no-console
      console.error("prefix not found");
    } else if (data) {
      let key = null;
      let decode = null;

      if (typeof data === "string") {
        if (data?.includes("key")) {
          key = extractKeyFromJSON(data);
        }
        decode = scramble(data, "decode");
      } else if (typeof data === "object") {
        const tempData = JSON.stringify(data);
        decode = scramble(tempData, "decode");
      }

      try {
        const info = JSON.parse(decode);
        if (info.time < new Date().getTime() - 60 * 60 * 24 * 2 * 1000) {
          const handler = new LicenseHandler(prefix, [info?.permalink]);
          handler.verifyLicense(key || info.key);
        }
        setAttributes({ isPremium: info?.activated || false });
      } catch (error) {
        setAttributes({ isPremium: false });
      }
    }
  }, [data]);

  return <span></span>;
};
export default BPLSDK;
