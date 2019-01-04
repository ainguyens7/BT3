import Api from "./api";
export default async function checkAccount(shop) {
  try {
    const result = await Api.getSetting(shop);
    const { status } = result;
    if (status === "error") {
      const { message, url } = result;
      return {
        status,
        message,
        url
      };
    } else {
      const { data: setting } = result;
      return {
        status,
        setting
      };
    }
  } catch (error) {
    console.log(error);
  }
}
