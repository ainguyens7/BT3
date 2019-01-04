export default async function request(url) {
  try {
    const result = await window.fetch(url, {
      method: "GET",
      credentials: "include",
      redirect: "manual"
    });
    return result;
  } catch (error) {
    throw error;
  }
}

export async function put(url, data) {
  try {
    const headers = new Headers();
    headers.append("Content-Type", "application/json");
    const response = await window.fetch(url, {
      method: "PUT",
      headers: headers,
      body: JSON.stringify(data)
    });
    return response;
  } catch (error) {
    throw error;
  }
}

export function getAlilink(id, callback) {
  const url = `https://app.oberlo.com/ajax/products/ali-url?id=${id}`;
  return $.get(url, response => {
    if (response.status === 1 && response.success) {
      return callback(response);
    }
    throw new Error("Could not get aliexpress link");
  });
}
