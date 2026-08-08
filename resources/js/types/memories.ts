export type Photo = {
    id: string;
    title: string;
    /** ISO 8601 date string — displayed as "July 18, 2026" or "July 2026". */
    taken_at: string;
    /** Gallery / thumbnail URL. */
    src: string;
    /** Full-size URL used by the viewer. */
    full: string;
    /** Natural pixel width — drives aspect ratio and column balancing. */
    width: number;
    /** Natural pixel height. */
    height: number;
    favorite: boolean;
    /**
     * True when the original can't be rendered in the browser — HEIC/HEIF
     * originals are stored without conversions, so an icon tile is shown.
     */
    unviewable?: boolean;
};
