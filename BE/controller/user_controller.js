import { Sequelize } from "sequelize";
import user from "../model/user_model.js";

export const getUsers = async(req, res) => {
    try{
        const respon = await user.findAll();
        respon.status(200).json(respon);
    }catch(error){
        console.log(error.message);
    }
}

