import express from "express";
import { Register, Login, refreshToken, Logout } from "../controllers/authController.js";

const router = express.Router();

router.post("/register", Register);
router.post("/login", Login);
router.get("/refresh", refreshToken);
router.delete("/logout", Logout);

export default router;