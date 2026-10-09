/**
 * What the fix handlers (`vulopilot_finding_fix_handler`,
 * `vulopilot_finding_bulk_fix_handler`) resolve to. `message` is what happened, including why when
 * nothing could be fixed; `undo` is present only when reversible. This plugin never interprets
 * this itself; it hands it to whatever view is registered (see useFixNotice).
 */
export interface FixOutcome {
	success: boolean;
	message: string;
	label?: string;
	/** Where to change what the fix reported on (e.g. the author's profile). */
	link?: { url: string; label: string };
	undo?: () => Promise<{ success: boolean; message: string }>;
	/**
	 * True when this outcome is the honest "no automatic fix exists for this kind of issue"
	 * case (never fixable, not a one-off failure) - callers use it to stop offering a
	 * "Fix with AI" button that can only ever fail the same way again.
	 */
	noFixAvailable?: boolean;
}
