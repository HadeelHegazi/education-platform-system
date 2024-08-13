const express = require("express");
const db = require("./routers/db-config.js");
const app = express();
const cookie = require("cookie-patser");
const PORT =process.env.PORT || 5000;
app.use("/js", express.static(_dirname+ "./puplic/js"))
app.set("view engine", "ejs");
app.set("views", "./views");
app.use(cookie());
app.use(express.json());
db.connect((err)=> {
    if(err) throw err;
    console.log("database connected");
})
app.listen(PORT);