import { Sequelize } from "sequelize";
import db from "../config/Database.js";

// Membuat tabel "users"
const User = db.define("users", {
  username: {
    type: Sequelize.STRING,
    allowNull: false
  },
  password: {
    type: Sequelize.STRING,
    allowNull: false
  },
  email: {
    type: Sequelize.STRING,
    allowNull: false
  },
},{
  timestamps: false // ✅ dipindahkan ke sini
}
);

db.sync().then(() => console.log("Users table synced"));

export default User;
