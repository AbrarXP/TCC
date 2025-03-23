import { Sequelize } from "sequelize";

// Nyambungin db ke BE
const db = new Sequelize("note_db", "admin", "bandengpresto", {
  host: "34.172.55.64",
  dialect: "mysql",
});

export default db;
