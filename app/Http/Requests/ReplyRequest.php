<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        return [
            'content' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [
            'content.min' => '回复最少两个字!',
        ];
    }

    public function withValidator($validator)
    {
        $content = $this->request->get('content');

        $content = clean($content, 'user_topic_body');

        if($content == "")
        {
            $validator->errors()->add( 'content', '回复内容为空!' );
            $this->failedValidation($validator);
        }

        $this->request->set('content', $content);
    }
}
