<?php

namespace Modules\Mailbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\User\Models\User\User;
use Modules\Mailbox\Models\MailboxAttachment;
use Modules\Mailbox\Repositories\MailboxRepository;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Mailbox\Http\Requests\ComposeMailRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class MailboxController
 *
 * Mailbox insert, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Mailbox
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class MailboxController extends Controller
{
    protected $mailboxRepo;
    protected $helper;

    /**
     * Instantiate a new repository instance.
     *
     * @param MailboxRepository $mailbox [Object]
     * @param EmailsHelper      $helper  [Object]
     *
     * @return void
     */
    public function __construct(
        MailboxRepository $mailbox,
        EmailsHelper $helper
    ) {
        $this->mailboxRepo = $mailbox;
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get email]
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->mailboxRepo->findAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ComposeMailRequest $request [Request for create email]
     *
     * @return Response
     */
    public function store(ComposeMailRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'created')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Variables
        $attachmentsFiles = [];

        // --
        // Get request inputs
        $type = $request->get('type');
        $attachments = $request->get('attachments');
        $input = $request->except('attachments');
        $fromEmail = \Auth::user()->email;

        // --
        // Attachments
        if (!empty($attachments)) {
            foreach ($attachments as $key => $value) {
                $attachments = MailboxAttachment::find($value);
                if (!empty($attachments)) {
                    $attachmentsFiles[$key] = [
                        'file' => Config('app.url').'/uploads/mailbox/'. $attachments->file_hash,
                        'file_name' => $attachments->file_name,
                        'file_extension' => $attachments->file_extension,
                        'file_size' => $attachments->file_size,
                        'mime_type' => $attachments->mime_type,
                        'file_hash' => $attachments->file_hash
                    ];
                }
            }
        }

        // --
        // Save draft
        if ($type == 'draft') {
            $input['to'] = serialize($input['to']);
            $input['from'] = $fromEmail;

            $this->mailboxRepo->create($input, $request, $attachmentsFiles);
        } else {
            // --
            // Send emails
            foreach ($input['to'] as $key => $value) {
                $users = User::find($value);
                try {
                    // --
                    // Send mail
                    $params = ['message_body' => $request->message_body];
                    $mailsConfig = [
                        'email'=> $users->email,
                        'name'=> $users->firstname.' '.$users->lastname,
                        'from'=> $fromEmail,
                        'subject'=> $request->subject
                    ];
                    $this->helper->sendmailAttachments(
                        'emails.mailbox_template',
                        $params,
                        $mailsConfig,
                        $attachmentsFiles
                    );

                    // --
                    // Save into DB
                    $input['to'] = $users->email;
                    $input['from'] = $fromEmail;

                    $this->mailboxRepo->create($input, $request, $attachmentsFiles);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        }
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'view')) {
            return response()->json("Access denied", 403);
        }
        return  $this->mailboxRepo->findById($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy email]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->mailboxRepo->destroy($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Mail has not been found.'], 400);
        }
    }

    /**
     * Upload file.
     *
     * @param Request $request [Request for upload attachment]
     *
     * @return Response
     */
    public function uploadAttachementFiles(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($request->hasFile('file')) {
            // --
            // Upload files
            $fileExt = $request->file('file')->getClientOriginalExtension();
            $fileDetails = [
                'size'=> $request->file('file')->getClientSize(),
                'fileName'=> $request->file('file')->getClientOriginalName(),
                'fileExt'=> $fileExt,
                'mimeType'=> $request->file('file')->getMimeType(),
                'path'=> uniqid().'.'.$fileExt
            ];

            $request->file('file')->move(
                public_path("/uploads/mailbox"), 
                $fileDetails['path']
            );
            $id = $this->mailboxRepo->uploadAttachementFiles($request, $fileDetails);
            if ($id) {
                return $id;
            } else {
                return response()->json(
                    ['error' => 'File has not been found.'], 
                    400
                );
            }
        } else {
            return response()->json(['error' => 'File has not been found.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for remove attachment]
     *
     * @return Response
     */
    public function removeAttachementFile(Request $request)
    {
        $request->validate(['id' => 'required']);

        if ($this->mailboxRepo->removeAttachementFile($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'File has not been found.'], 400);
        }
    }

    /**
     * Mark email unfavourite.
     *
     * @param Request $request [Request for update email]
     *
     * @return Response
     */
    public function setUnFavouriteMail(Request $request)
    {
        $request->validate(
            [
            'id' => 'required'
            ]
        );
           
        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->mailboxRepo->setUnFavouriteMail($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Mail has not been found.'], 400);
        }
    }

    /**
     * Mark email read.
     *
     * @param Request $request [Request for update email]
     *
     * @return Response
     */
    public function setReadMail(Request $request)
    {
        $request->validate(
            [
            'ids' => 'required'
            ]
        );

        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->mailboxRepo->markAsRead($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Mail has not been found.'], 400);
        }
    }

    /**
     * Mark email favourite.
     *
     * @param Request $request [Request for update email]
     *
     * @return Response
     */
    public function setFavouriteMail(Request $request)
    {
        $request->validate(
            [
            'ids' => 'required'
            ]
        );

        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->mailboxRepo->markAsFavourite($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Mail has not been found.'], 400);
        }
    }

    /**
     * Remove mails.
     *
     * @param Request $request [Request for remove email]
     *
     * @return Response
     */
    public function removeMarkMails(Request $request)
    {
        $request->validate(
            [
            'ids' => 'required'
            ]
        );

        // --
        // Check role/permission
        if (!AdminHelper::can_action(14, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->mailboxRepo->removeMarkMails($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Mail has not been found.'], 400);
        }
    }

    /**
     * Get listing of unread mails.
     *
     * @return Response
     */
    public function getUnReadMailByUser(Request $request)
    {
        return $this->mailboxRepo->getUnReadMailByUser($request);
    }
}
