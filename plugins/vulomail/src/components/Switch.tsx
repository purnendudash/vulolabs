import { ToggleInput } from '@zyra/inputs';

interface SwitchProps {
	name: string;
	label: string;
	checked: boolean;
	// eslint-disable-next-line no-unused-vars
	onChange: (checked: boolean) => void;
}

/**
 * On/off switch: zyra's ToggleInput in its single-option multi-select form, the same way the other
 * VuloLabs admin screens render a boolean.
 */
const Switch = ({ name, label, checked, onChange }: SwitchProps) => (
	<ToggleInput
		options={[{ key: name, value: name, label }]}
		value={checked ? [name] : []}
		multiSelect
		modules={[]}
		onChange={() => onChange(!checked)}
	/>
);

export default Switch;
