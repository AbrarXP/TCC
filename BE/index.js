import express from "express";
import cors from "cors";
import NoteRoute from "./routes/NoteRoute.js";

const PORT = process.env.PORT || 5000;

const app = express();

app.use(cors());
app.use(express.json());
app.use(NoteRoute);

app.listen(PORT, () => console.log("Server connected ${PORT}"));
