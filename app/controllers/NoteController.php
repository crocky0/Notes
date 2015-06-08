<?php

class NoteController extends BaseController
{
    private $user = 0;
    public function __construct()
    {
        if(Session::has('user_id'))
            $this->user =  Session::get('user_id');
        Session::clear();
    }
    public function index()
    {
        if (!$this->checkUser())
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $note = Note::where('userid', '=', $this->user)-> get(['id', 'title', 'note']);
        return Response::json(array('state' => 200, 'notes' => (is_null($note) ? array() : $note->toArray())));
    }
    public function show($id)
    {
        if (!$this->checkUser())
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $note = Note::where('userid', '=', $this->user)->where('id', '=', $id)->get(['id', 'title', 'note']);
        return Response::json(array('state' => 200, 'notes' => (is_null($note) ? array() : $note->toArray())));
    }
    public function store()
    {
        if (!$this->checkUser())
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $json = array();
        $note = new Note;
        $note->title = strip_tags(Input::get('title', ''));
        $note->userid = $this->user;
        $note->note = strip_tags(Input::get('note', ''));
        $validation = Validator::make($note->toArray(), Note::getValidationRules());
        if ($validation->fails())
        {
            $json['message'] = $validation->messages();
            $json['state'] = 400;
        }
        else
        {
            $note->save();
            $json['message'] = "note is inserted";
            $json['state'] = 200;
        }
        return Response::json($json);
    }
    public function update($id)
    {
        if (!$this->checkUser())
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $json = array();
        $note = Note::where('userid', '=', $this->user)->find($id);
        if(is_null($note))
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $note->title = strip_tags(Input::get('title', ''));
        $note->note = strip_tags(Input::get('note', ''));
        $validation = Validator::make($note->toArray(), Note::getValidationRules());
        if ($validation->fails())
        {
            $json['messages'] = $validation->messages();
            $json['state'] = 400;
        }
        else
        {
            $note->save();
            $json['state'] = 200;
            $json['message'] = "note is updated";
        }
        return Response::json($json);
    }
    public function destroy($id)
    {
        if (!$this->checkUser())
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
            return Response::json($json);
        }
        $json = array();
        $note = Note::where('userid', $this->user)->find($id);
        if (is_null($note))
        {
            $json['state'] = 400;
            $json['message'] = "note doesn't exists";
        }
        else
        {
            $json['state'] = 200;
            $json['message'] = "note is deleted";
            $note->delete();
        }
        return Response::json($json);
    }
    private function checkUser()
    {
        if ($this->user == 0)
        {
            Log::error("User id iz 0");
            return false;
        }
        return true;
    }
}