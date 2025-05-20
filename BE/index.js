import express from "express";
import cors from "cors";
import NoteRoute from "./routes/NoteRoute.js";

const app = express();

app.use(cors({
  origin: "https://fe-dot-hazel-airlock-451115-h0.et.r.appspot.com",  // domain FE kamu
  credentials: true
}));
app.use(express.json());
app.use(NoteRoute);

app.get("/", (req, res) => {
  res.send("Hello from backend!");
  res.cookie("token", token, {
    httpOnly: false,
    sameSite: "None",   // ⬅️ WAJIB
    secure: true        // ⬅️ WAJIB jika SameSite=None
    });
});

const PORT = 5000;
app.listen(PORT, () => console.log("Server connected ${PORT}"));
