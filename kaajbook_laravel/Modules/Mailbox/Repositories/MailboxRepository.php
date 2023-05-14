<?php

namespace Modules\Mailbox\Repositories;

use Modules\User\Models\User\User;
use Modules\Mailbox\Models\Mailbox;
use Modules\Mailbox\Models\MailboxAttachment;

use Auth;
use DB;
use \Carbon\Carbon;

/**
 * Class MailboxRepository
 *
 * Mailbox create, update, delete and view.
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
class MailboxRepository
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request [Request for get email]
	 *
	 * @return Object
	 */
	public function findAll($request)
	{
		$mailbox = [];
		$totalData = 0;
		$countUnRead = 0;
		$countDraft = 0;
		$totalPage = 0;
		$totalData = 0;
		$logginUser = Auth::user();

		if(!empty($logginUser)) {
			$to = $logginUser->email;
			$user_id = $logginUser->id;
			
			$start = $request->input('start');
			$limit = $request->input('length');
			$search = ($request->has('search')) ? $request->input('search') : '';

			if ($request->type == 'inbox') {
				$mailbox = Mailbox::with(
					array('user' => function ($query) {
						$query->select('id', 'firstname', 'lastname', 'email');
					})
				)
				->with('attachments')
				->where('to', $to);
				if (!empty($search)) {
					$mailbox->Where(
						function ($sq) use ($search) {
							$sq->where('subject', 'LIKE', "%{$search}%")
								->orWhere('created_at', 'LIKE', "%{$search}%");
						}
					);
				}
				$totalData = $mailbox->count();
				$mailbox = $mailbox->offset($start)
					->limit($limit)
					->orderBy('created_at', 'DESC')
					->get()
					->toArray();
			}

			if ($request->type == 'sent') {
				$mailbox = Mailbox::with(
					array('user' => function ($query) use ($to) {
						$query->where('email', $to)->
							select('id', 'firstname', 'lastname', 'email');
					})
				)
				->with('attachments')
				->where('from', $to)
				->where('type', 'inbox');

				if (!empty($search)) {
					$mailbox->Where(
						function ($sq) use ($search) {
							$sq->where('subject', 'LIKE', "%{$search}%")
								->orWhere('created_at', 'LIKE', "%{$search}%");
						}
					);
				}

				$totalData = $mailbox->count();

				$mailbox = $mailbox->offset($start)
					->limit($limit)
					->orderBy('created_at', 'DESC')
					->get()
					->toArray();
			}

			if ($request->type == 'favourite') {
				$mailbox = Mailbox::with(
					array('user' => function ($query) {
						$query->select('id', 'firstname', 'lastname', 'email');
					})
				)
					->with('attachments')
					->Where(
						function ($query) use ($to) {
							$query->where(
								[
								['to', '=', $to],
								['type', '=', 'inbox'],
								['favourites', '=', 1],
								]
							)
								->orWhere(
									[
									['from', '=', $to],
									['type', '=', 'inbox'],
									['favourites', '=', 1],
									]
								);
						}
					);

				if (!empty($search)) {
					$mailbox->Where(
						function ($sq) use ($search) {
							$sq->where('subject', 'LIKE', "%{$search}%")
								->orWhere('created_at', 'LIKE', "%{$search}%");
						}
					);
				}

				$totalData = $mailbox->count();

				$mailbox = $mailbox->offset($start)
					->limit($limit)
					->orderBy('created_at', 'DESC')
					->get()
					->toArray();
			}

			if ($request->type == 'draft') {
				$mailbox = Mailbox::with(
					array('user' => function ($query) {
						$query->select('id', 'firstname', 'lastname', 'email');
					})
				)
					->with('attachments')
					->where('user_id', $user_id)
					->where('type', 'draft');

				if (!empty($search)) {
					$mailbox->Where(
						function ($sq) use ($search) {
							$sq->where('subject', 'LIKE', "%{$search}%")
								->orWhere('created_at', 'LIKE', "%{$search}%");
						}
					);
				}

				$totalData = $mailbox->count();

				$mailbox = $mailbox->offset($start)
					->limit($limit)
					->orderBy('created_at', 'DESC')
					->get()
					->toArray();
			}

			if ($request->type == 'trash') {
				if ($request->trashType == 'inbox') {
					$mailbox = Mailbox::with(
						array('user' => function ($query) {
							$query->select('id', 'firstname', 'lastname', 'email');
						})
					)
						->with('attachments')
						->where('to', $to)
						->onlyTrashed();

					if (!empty($search)) {
						$mailbox->Where(
							function ($sq) use ($search) {
								$sq->where('subject', 'LIKE', "%{$search}%")
									->orWhere('created_at', 'LIKE', "%{$search}%");
							}
						);
					}

					$totalData = $mailbox->count();

					$mailbox = $mailbox->offset($start)
						->limit($limit)
						->orderBy('created_at', 'DESC')
						->get()
						->toArray();
				}

				if ($request->trashType == 'sent') {
					$mailbox = Mailbox::with(
						array('user' => function ($query) {
							$query->select('id', 'firstname', 'lastname', 'email');
						})
					)
						->with('attachments')
						->where('user_id', $user_id)
						->where('type', 'inbox')
						->onlyTrashed();

					if (!empty($search)) {
						$mailbox->Where(
							function ($sq) use ($search) {
								$sq->where('subject', 'LIKE', "%{$search}%")
									->orWhere('created_at', 'LIKE', "%{$search}%");
							}
						);
					}

					$totalData = $mailbox->count();

					$mailbox = $mailbox->offset($start)
						->limit($limit)
						->orderBy('created_at', 'DESC')
						->get()
						->toArray();
				}

				if ($request->trashType == 'draft') {
					$mailbox = Mailbox::with(
						array('user' => function ($query) {
							$query->select('id', 'firstname', 'lastname', 'email');
						})
					)
						->with('attachments')
						->where('user_id', $user_id)
						->where('type', 'draft')
						->onlyTrashed();

					if (!empty($search)) {
						$mailbox->Where(
							function ($sq) use ($search) {
								$sq->where('subject', 'LIKE', "%{$search}%")
									->orWhere('created_at', 'LIKE', "%{$search}%");
							}
						);
					}

					$totalData = $mailbox->count();

					$mailbox = $mailbox->offset($start)
						->limit($limit)
						->orderBy('created_at', 'DESC')
						->get()
						->toArray();
				}
			}

			$countUnRead =  Mailbox::select('to')
				->where('to', $to)
				->where('view_status', 2)
				->count();
		
			$countDraft = Mailbox::select('to')
				->where('user_id', $user_id)
				->where('type', 'draft')
				->count();

			$totalPage = ceil($totalData / $limit);
		}

		return compact(
			'mailbox',
			'countUnRead',
			'countDraft',
			'totalPage',
			'totalData'
		);
	}

	/**
	 * Display a announcement of the resource.
	 *
	 * @param Int $id [Row id]
	 *
	 * @return Object
	 */
	public function findById($id)
	{
		$to = Auth::user()->email;
		$user_id = Auth::user()->id;

		$mailbox = [];

		$mailbox = Mailbox::find($id);
		if ($mailbox) {
			$mailbox->view_status = ($mailbox->user_id == $user_id) ? $mailbox->view_status : 1;
			$mailbox->save();
			$mailbox['attachments'] = MailboxAttachment::where('mailbox_id', $id)
				->get();
		}

		$countUnRead =  Mailbox::select('to')
			->where('to', $to)
			->where('view_status', 2)
			->count();
		
		$countDraft = Mailbox::select('to')
			->where('user_id', $user_id)
			->where('type', 'draft')
			->count();
		
		return compact('mailbox', 'countUnRead', 'countDraft');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Array   $input            [Input arrsy]
	 * @param Request $request          [Request for create email]
	 * @param Array   $attachmentsFiles [Attachments array]
	 *
	 * @return Boolean
	 */
	public function create($input, $request, $attachmentsFiles = [])
	{
		$users = Auth::user();
		// --
		// Save mails
		$mailbox = new Mailbox;
		$mailbox->fill($input);
		if ($users->mails()->save($mailbox)) {
			// --
			// Update attachment files ids
			if (!empty($attachmentsFiles)) {
				foreach ($attachmentsFiles as $key => $value) {
					$updateRes = MailboxAttachment::where(
						'file_hash',
						$value['file_hash']
					)
						->whereNull('mailbox_id')
						->update(array('mailbox_id' => $mailbox->id));
					if (!$updateRes) {
						// --
						// Save file
						$mailBoxAttachment = new MailboxAttachment();
						$mailBoxAttachment->mailbox_id = $mailbox->id;
						$mailBoxAttachment->file_name = $value['file_name'];
						$mailBoxAttachment->file_extension = $value['file_extension'];
						$mailBoxAttachment->file_size = $value['file_size'];
						$mailBoxAttachment->file_hash = $value['file_hash'];
						$mailBoxAttachment->mime_type = $value['mime_type'];
						$mailBoxAttachment->save();
					}
				}
			}

			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);

			// Add Notification.
			$mail_to = User::where('email', $mailbox->to)->first();
            createNotification([$mail_to], 'mailbox', $mailbox->id, 'Mail Received.', $mailbox->subject);

			return true;
		}
		return false;
	}

	/**
	 * Store a newly uploaded file in storage.
	 *
	 * @param Request $request     [Request for upload attachment]
	 * @param Array   $fileDetails [Attachment details]
	 *
	 * @return Boolean
	 */
	public function uploadAttachementFiles($request, $fileDetails)
	{
		// --
		// Save file
		$mailBoxAttachment = new MailboxAttachment();
		$mailBoxAttachment->file_name = $fileDetails['fileName'];
		$mailBoxAttachment->file_extension = $fileDetails['fileExt'];
		$mailBoxAttachment->file_size = $fileDetails['size'];
		$mailBoxAttachment->file_hash = $fileDetails['path'];
		$mailBoxAttachment->mime_type = $fileDetails['mimeType'];

		if ($mailBoxAttachment->save()) {
			// --
			// Add activities
			// createUserActivity(
			//     Mailbox::MODULE_NAME,
			//     $mailBoxAttachment->id,
			//     $request->method(),
			//     $mailBoxAttachment->file_name,
			//     $request->ip()
			// );
			return $mailBoxAttachment->id;
		} else {
			return false;
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request [Request for remove attachment]
	 *
	 * @return Boolean
	 */
	public function removeAttachementFile($request)
	{
		$id = $request->get('id');
		$mailboxAttachment = MailboxAttachment::findOrFail($id);
		// --
		// Remove file from folder
		$file = public_path() .'/uploads/mailbox/'. $mailboxAttachment->file_hash;
		if (file_exists($file)) {
			unlink($file);
		}
		if ($mailboxAttachment->delete()) {
			// --
			// Add activities
			// createUserActivity(
			//     Mailbox::MODULE_NAME,
			//     $mailboxAttachment->id,
			//     $request->method(),
			//     $mailboxAttachment->file_name,
			//     $request->ip()
			// );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Mark email unfavourite.
	 *
	 * @param Request $request [Request for update email]
	 *
	 * @return Boolean
	 */
	public function setUnFavouriteMail($request)
	{
		$id=$request->get('id');
		$mailbox = Mailbox::findOrFail($id);
		$mailbox->favourites = 0;

		if ($mailbox->save()) {
			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);
			return true;
		}
		return false;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request [Request for destroy email]
	 * @param Int     $id      [Row id]
	 *
	 * @return Boolean
	 */
	public function destroy($request, $id)
	{
		$mailbox = Mailbox::findOrFail($id);

		if ($mailbox->delete()) {
			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Mark email read.
	 *
	 * @param Request $request [Request for update email]
	 *
	 * @return Boolean
	 */
	public function markAsRead($request)
	{
		$ids = $request->get('ids');
		
		foreach ($ids as $key => $value) {
			$mailbox = Mailbox::findOrFail($value);
			$mailbox->view_status = 1;
			$mailbox->save();

			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);
		}
		return true;
	}

	/**
	 * Mark email favourite.
	 *
	 * @param Request $request [Request for update email]
	 *
	 * @return Boolean
	 */
	public function markAsFavourite($request)
	{
		$ids = $request->get('ids');

		foreach ($ids as $key => $value) {
			$mailbox = Mailbox::findOrFail($value);
			$mailbox->favourites = 1;
			$mailbox->save();

			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);
		}
		return true;
	}

	/**
	 * Remove mark mails.
	 *
	 * @param Request $request [Request for update email]
	 *
	 * @return Boolean
	 */
	public function removeMarkMails($request)
	{
		$ids = $request->get('ids');

		foreach ($ids as $key => $value) {
			$mailbox = Mailbox::findOrFail($value);
			$mailbox->user_id = Auth::user()->id;
			$mailbox->save();
			$mailbox->delete();

			// --
			// Add activities
			createUserActivity(
				Mailbox::MODULE_NAME,
				$mailbox->id,
				$request->method(),
				$mailbox->subject,
				$request->ip()
			);
		}
		return true;
	}

	/**
	 * Get listing of unread mails.
	 *
	 * @return Array
	 */
	public function getUnReadMailByUser($request)
	{
		$unReadEmail = [];
		$nowTime = Carbon::now();
		$matchThese = [['created_at','>=',Carbon::now()->subDay()]];
		$unReadEmail = Mailbox::with(
			array('user' => function ($query) {
				$query->select('id', 'firstname', 'lastname', 'email', 'avatar', 'is_active');
			})
		)
			->where('to', Auth::user()->email)
			->where('type', 'inbox')
			->where('view_status', 2)
			->orderBy('created_at', 'DESC')
			->limit($request->get('length'))
			->get();

		foreach ($unReadEmail as $key => $value) {
			$oldTime = Carbon::parse($value['created_at']);
			$unReadEmail[$key]['timeDiff'] = $oldTime->diffForHumans($nowTime);
		}
		return $unReadEmail;
	}
}
