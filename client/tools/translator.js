const axios = require("axios");
const fs = require("fs");
const path = require("path");
// get your Deepl API key from their website
const apiKey = "21c74419-4d32-bafb-387d-899a0e6cc67a:fx";

const languages = ["pl", "de", "fr", "pt", "ru", "uk", "it", "cs"];

fs.readFile(
  path.join(__dirname, "../src/locale/en.json"),
  "utf8",
  (err, data) => {
    if (err) {
      console.error(err);
      return;
    }
    // Parse the JSON data
    const en = JSON.parse(data);
    console.log(en);

    for (const lang of languages) {
      let translated = {};
      const langFileName = path.join(__dirname, `../src/locale/${lang}.json`);
      fs.access(langFileName, fs.constants.F_OK, (err) => {
        if (err) {
          // If the file doesn't exist, create it and write some data to it
          fs.writeFile(langFileName, JSON.stringify(translated), (err) => {
            if (err) {
              console.error(err);
              return;
            }
            console.log(`File ${langFileName} created successfully`);
          });
          return;
        }
        fs.readFile(langFileName, "utf8", (err, data) => {
          translated = JSON.parse(data);

          for (const [key, value] of Object.entries(en)) {
            if (!translated[key]) {
              const url = `https://api-free.deepl.com/v2/translate?auth_key=${apiKey}&text=${encodeURIComponent(
                value
              )}&target_lang=${lang.toUpperCase()}`;
              axios
                .get(url)
                .then((response) => {
                  console.log(response.data);
                  translated[key] = response.data.translations[0].text;
                  fs.writeFileSync(langFileName, JSON.stringify(translated)); // update the translated value in the object
                })
                .catch((error) => console.error(error));
            }
          }
        });
      });
    }
  }
);
