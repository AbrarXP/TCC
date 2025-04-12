import express from "express";
import cors from "cors";
import NoteRoute from "./routes/NoteRoute.js";

const app = express();

app.use(cors());
app.use(express.json());
app.use(NoteRoute);

app.get("/", (req, res) => {
  res.send("Hello from backend!");
});

const PORT = 5000;
app.listen(PORT, () => console.log("Server connected ${PORT}"));
