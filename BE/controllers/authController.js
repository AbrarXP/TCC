import bcrypt from "bcryptjs";
import jwt from "jsonwebtoken";
import User from "../models/userModel.js"; 

export const Register = async (req, res) => {
  const { username, email, password } = req.body;
  try {
    // Cek apakah email sudah terdaftar
    const existingEmail = await User.findOne({ where: { email } });
    if (existingEmail) return res.status(400).json({status: "Error", msg: "Email sudah digunakan" });

    // Cek apakah email sudah terdaftar
    const existingUsername = await User.findOne({ where: { username } });
    if (existingUsername) return res.status(400).json({status: "Error", msg: "Username sudah digunakan" });

    // Hash password
    const salt = await bcrypt.genSalt();
    const hashPassword = await bcrypt.hash(password, salt);

    // Simpan user baru
    await User.create({
      username,
      email,
      password: hashPassword,
    });

    res.status(201).json({status: "Sukses", msg: "Registrasi berhasil" });
  } catch (error) {
    res.status(500).json({ msg: error.message });
  }
};

export const Login = async (req, res) => {
  const { username, password } = req.body;
  try {
    // Cek usernya ada ga
    const user = await User.findOne({ where: { username } });
    if (!user) return res.status(404).json({status: "Error", msg: "User tidak ditemukan" });

    // Cek pw sama username sama ga
    const match = await bcrypt.compare(password, user.password);
    if (!match) return res.status(400).json({status: "Error", msg: "Password salah" });

    // Bikin token
    const accessToken = jwt.sign(
      { userId: user.id, username: user.username },
      process.env.ACCESS_TOKEN_SECRET,
      { expiresIn: "1d" }
    );

    const refreshToken = jwt.sign(
      { userId: user.id },
      process.env.REFRESH_TOKEN_SECRET,
      { expiresIn: "7d" }
    );

    // Kirim via cookie
    res.cookie("accessToken", accessToken, {
        httpOnly: false,
        secure: false, // Gunakan hanya jika pakai HTTPS
        sameSite: "None", // atau "Lax"
        maxAge: 15 * 60 * 1000 // 15 menit
    });

    res.cookie("refreshToken", refreshToken, {
        httpOnly: false,
        secure: false,
        sameSite: "None",
        maxAge: 7 * 24 * 60 * 60 * 1000 // 7 hari
    });

    res.json({
    status:"Sukses",
    accessToken: accessToken,
      msg: "Login berhasil",
      user: {
        id: user.id,
        username: user.username,
      }
    });

  } catch (error) {
    res.status(500).json({ msg: error.message });
  }
};

export const Logout = (req, res) => {
  res.clearCookie("accessToken", { httpOnly: false, sameSite: "None", secure: false });
  res.clearCookie("refreshToken", { httpOnly: false, sameSite: "None", secure: false });
  res.json({status:"Sukses", msg: "Logout berhasil" });
};

export const refreshToken = async (req, res) => {
  const token = req.cookies.refreshToken;
  if (!token) return res.status(401).json({status: "Error", msg: 'Token tidak tersedia' });

  try {
    const decoded = jwt.verify(token, process.env.REFRESH_TOKEN_SECRET);

    // Ambil ulang data user dari database berdasarkan userId
    const user = await User.findByPk(decoded.userId);
    if (!user) return res.status(404).json({status: "Error", msg: "User tidak ditemukan" });

    const newAccessToken = jwt.sign(
      { userId: user.id, username: user.username },
      process.env.ACCESS_TOKEN_SECRET,
      { expiresIn: '15m' }
    );

    res.cookie("accessToken", newAccessToken, {
      httpOnly: false,
      secure: false,
      sameSite: "None",
      maxAge: 15 * 60 * 1000 // 15 menit
    });

    res.json({
      msg: "Token diperbarui",
      accessToken: newAccessToken,
      user: {
        id: user.id,
        username: user.username
      }
    });
  } catch (err) {
    return res.status(403).json({ msg: 'Token tidak valid' });
  }
};

