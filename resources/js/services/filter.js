export function setInputFilter(textbox, inputFilter) {
	[
		'input',
		'keydown',
		'keyup',
		'mousedown',
		'mouseup',
		'select',
		'contextmenu',
		'drop',
		'blur',
		'change',
	].forEach(event => {
		textbox.addEventListener(event, () => {
			const input = textbox;
			if (inputFilter(input.value)) {
				input.oldValue = input.value;
				input.oldSelectionStart = input.selectionStart;
				input.oldSelectionEnd = input.selectionEnd;
			} else if (Object.prototype.hasOwnProperty.call(input, 'oldValue')) {
				input.value = input.oldValue;
				input.setSelectionRange(input.oldSelectionStart, input.oldSelectionEnd);
			}
		});
	});
}

function filterListener() {}

export const positiveNumFlt = value => {
	return /^\d*$/.test(value);
};
export const deciamlNumFlt = value => {
	return /^-?\d*(\.\d*)?$/.test(value);
};
export const integerNumFlt = value => {
	return /^-?\d*$/.test(value);
};

export const letterNumFlt = value => {
	return /^[0-9a-z]*$/i.test(value);
};
