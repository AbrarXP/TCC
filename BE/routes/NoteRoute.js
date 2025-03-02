import express from "express";
import { getNotes, createNote, updateNote, deleteNote } from "../controllers/NoteController.js";
const router = express.Router();

router.get("/notes", getNotes);
router.post("/add-note", createNote);
router.put("/edit-note/:id", updateNote);
router.delete("/delete-note/:id", deleteNote);

export default router;
