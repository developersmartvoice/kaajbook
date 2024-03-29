export class Admin {
}

// --
// User avtars.
export const UserAvatars = [
	'1-man.png',
	'2-man.png',
	'3-man.png',
	'4-man.png',
	'5-man.png',
	'6-man.png',
	'1-woman.png',
	'2-woman.png',
	'3-woman.png',
	'4-woman.png',
	'5-woman.png',
	'6-woman.png'
];

//--
// Meetings
export const meeting_status_key_value = [];
meeting_status_key_value[1] = 'common.status.open';
meeting_status_key_value[2] = 'common.status.in_progress';
meeting_status_key_value[3] = 'common.status.cancel';
meeting_status_key_value[4] = 'common.status.completed';

export const meeting_status_key_class = [];
meeting_status_key_class[1] = 'open';
meeting_status_key_class[2] = 'in_progress';
meeting_status_key_class[3] = 'cancel';
meeting_status_key_class[4] = 'completed';

// ngx-editor config.
export const editorConfig = {
	editable: true,
	spellcheck: true,
	height: '100px',
	minHeight: '50px',
	placeholder: '',
	translate: 'no',
	"toolbar": [
		["bold", "italic", "underline", "strikeThrough", "superscript", "subscript"],
		["fontName", "fontSize", "color"],
		["justifyLeft", "justifyCenter", "justifyRight", "justifyFull", "indent", "outdent"],
		["cut", "copy", "delete", "removeFormat", "undo", "redo"],
		["paragraph", "blockquote", "removeBlockquote", "horizontalLine", "orderedList", "unorderedList"],
		["link", "unlink"]
	]
};

// Datepicker config.
export const datepickerConfig = {
	dateInputFormat: 'YYYY-MM-DD',
	containerClass: 'theme-red'
}
