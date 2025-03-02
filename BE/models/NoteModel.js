import { Sequelize } from "sequelize";
import db from "../config/Database.js";

// Membuat tabel "user"
const Note = db.define(
  "note", // Nama Tabel
  {
    title: Sequelize.TEXT,
    content: Sequelize.TEXT,
  }
);

db.sync().then(() => console.log("Database synced"));

export default Note;
