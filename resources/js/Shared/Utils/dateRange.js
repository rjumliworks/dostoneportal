/**
 * Formats a start/end date pair (YYYY-MM-DD strings) as a readable period, e.g.:
 *   - Same day:            "September 12, 2026"
 *   - Same month/year:     "September 12-23, 2026"
 *   - Different month:     "September 29 - October 2, 2026"
 *   - Different year:      "December 29, 2026 - January 2, 2027"
 *   - No end date yet:     "September 12, 2026" (or "September 12, 2026 - Present"
 *                           when ongoingLabel is given)
 *
 * @param {string|null} startAt
 * @param {string|null} endAt
 * @param {string|null} ongoingLabel shown after the start date when there's no end date
 */
export function formatDateRange(startAt, endAt, ongoingLabel = null) {
    if (!startAt) return '-';

    const start = new Date(startAt + 'T00:00:00');
    if (isNaN(start)) return '-';

    const monthLong = (d) => d.toLocaleString('en-US', { month: 'long' });
    const startText = `${monthLong(start)} ${start.getDate()}, ${start.getFullYear()}`;

    if (!endAt) {
        return ongoingLabel ? `${startText} - ${ongoingLabel}` : startText;
    }

    const end = new Date(endAt + 'T00:00:00');
    if (isNaN(end)) return startText;

    if (startAt === endAt) return startText;

    const sameYear = start.getFullYear() === end.getFullYear();
    const sameMonth = sameYear && start.getMonth() === end.getMonth();

    if (sameMonth) {
        return `${monthLong(start)} ${start.getDate()}-${end.getDate()}, ${start.getFullYear()}`;
    }

    if (sameYear) {
        return `${monthLong(start)} ${start.getDate()} - ${monthLong(end)} ${end.getDate()}, ${start.getFullYear()}`;
    }

    return `${startText} - ${monthLong(end)} ${end.getDate()}, ${end.getFullYear()}`;
}
