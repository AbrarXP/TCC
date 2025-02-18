import { Sequelize } from "sequelize";
import db from "../db/db.js";

const {DataTypes} = Sequelize;

const user  = db.define(
    "users",
    {
        name: DataTypes.STRING,
        email: DataTypes.STRING,
        gender: DataTypes.STRING,
    },
    {
        freezeTableName: true
    }
)

export default user;

(async()=> {
    await db.sync();
})();