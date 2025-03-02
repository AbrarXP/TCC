import Note from "../models/NoteModel.js";

// GET
async function getNotes(req, res) {
  try {
    const response = await Note.findAll();
    res.status(200).json(response);y
  } catch (error) {
    console.log(error.message);
  }
}

// CREATE
async function createNote(req, res) {
  try {
    const inputResult = req.body;
    await Note.create(inputResult);
    res.status(201).json({ msg: "Note Created" });
  } catch (error) {
    console.log(error.message);
  }
}

export { getNotes, createNote};

export const updateNote = async(req, res) =>{
  try {
    const inputResult = req.body
    await Note.update(inputResult, {
      where :{
        id: req.params.id
      }
    });
    res.status(200).json({msg:"Note berhasil di update !"})
  } catch (error) {
    console.log(error);
  }
};

export const deleteNote = async(req, res) =>{
  try {
    const inputResult = req.body
    await Note.destroy({
      where :{
        id: req.params.id
      }
    });
    res.status(200).json({msg:"Note berhasil di hapus !"})
  } catch (error) {
    console.log(error);
  }
};
