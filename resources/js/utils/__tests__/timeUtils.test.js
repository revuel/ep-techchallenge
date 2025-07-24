import { formatBookingTime } from '../../utils/timeUtils';

describe('formatBookingTime', () => {
  it('formats the booking time correctly', () => {
    const start = '2024-12-01T07:53:00.000Z';
    const end = '2024-12-01T08:38:18.000Z';
    const formatted = formatBookingTime(start, end);
    expect(formatted).toBe('Sunday, 1 December 2024, 07:53 to 08:38');
  });
});