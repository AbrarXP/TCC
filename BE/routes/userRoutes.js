import e from "express";
import { getUsers } from "../controller/user_controller.js";

const routes = e.Router();

routes.get("/model", getUsers);
export default routes;