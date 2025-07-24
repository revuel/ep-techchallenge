/**
 * Transforms start and end datetime tuples 
 * into this format: Monday 19 January 2020, 14:00 to 15:00
 * 
 * @param {*} start string with datetime format (2024-12-01T07:53:18.000000Z)
 * @param {*} end string with datetime format (2024-12-01T07:53:18.000000Z)
 * @param {*} locale string joining ISO 639-1 language code and ISO 3166-1 country code
 * @returns 
 */
export function formatBookingTime(start, end, locale = 'en-GB') {
  const startDate = new Date(start);
  const endDate = new Date(end);

  const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
  const optionsTime = { hour: '2-digit', minute: '2-digit', hour12: false };

  const formattedDate = startDate.toLocaleDateString(locale, optionsDate);
  const formattedStartTime = startDate.toLocaleTimeString(locale, optionsTime);
  const formattedEndTime = endDate.toLocaleTimeString(locale, optionsTime);

  return `${formattedDate}, ${formattedStartTime} to ${formattedEndTime}`;
}