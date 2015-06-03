<?php

class NoteController extends BaseController
{
    public function index()
    {
        $note = Note::where('userid', '=', 1)-> get(['id', 'title', 'note']);
        return Response::json(array('state' => 200, 'notes' => (is_null($note) ? array() : $note->toArray())));
    }

    public function show($id)
    {
        $note = Note::where('userid', '=', 1)->where('id', '=', $id)->get(['id', 'title', 'note']);
        return Response::json(array('state' => 200, 'notes' => (is_null($note) ? array() : $note->toArray())));
    }

    public function store()
    {
        $json = array();
         if (!Request::isJson())
        {
            $json['message'] = "Request is not JSON";
            $json['state'] = 400;
            return Response::json($json);
        }
        $note = new Note;
        $note->title = strip_tags(Input::get('title', ''));
        $note->userid = 1;
        $note->note = strip_tags(Input::get('note', ''));
        $validation = Validator::make($note->toArray(), Note::getValidationRules());
        if ($validation->fails())
        {
            //$json['error Messages'] = $validator->messages()->toArray();
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
        $json = array();
        if (!Request::isJson())
        {
            $json['message'] = "Request is not JSON";
            $json['state'] = 400;
            return Response::json($json);
        }
        $note = Note::where('userid', 1)->find($id);
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
            //$json['error Messages'] = $validator->messages()->toArray();
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
        $json = array();
        $note = Note::where('userid', 1)->find($id);
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
}
