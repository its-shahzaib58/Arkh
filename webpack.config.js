const path = require("path");

module.exports = {
    entry: "./src/main.js", // Path to main file where `createDocxFile` is defined
    output: {
        filename: "bundle.js",
        path: path.resolve(__dirname, "dist"),
        library: "MyLibrary", // Global variable name
        libraryTarget: "var", // Export as a global variable
    },
    mode: "production",
    resolve: {
        alias: {
            docx: path.resolve(__dirname, "node_modules/docx"),
        },
    },
};
