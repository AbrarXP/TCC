import express from "express";
import cors from "cors";
import cookieParser from "cookie-parser"; // jika ingin baca cookie
import NoteRoute from "./routes/NoteRoute.js";

const app = express();

app.use(cors());
app.use(express.json());
app.use(cookieParser());
app.use(NoteRoute);

// Test endpoint untuk kirim cookie dummy
app.get("/", (req, res) => {
  res.cookie("token", "tes-token-dummy", {
    httpOnly: true,
    sameSite: "None",
    secure: true
  });
  res.send("Hello from backend! Cookie dikirim.");
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server connected on port ${PORT}`));
