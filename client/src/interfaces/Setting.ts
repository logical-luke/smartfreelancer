type DayOfWeek = 'monday' | 'tuesday' | 'wednesday' | 'thursday' | 'friday' | 'saturday' | 'sunday';

type TimeString = `${number}:${number}` | '';

interface WorkdayTime {
  start: TimeString;
  end: TimeString;
}

type WorkdaySettings = {
  [key in DayOfWeek]: WorkdayTime;
};

export type { WorkdaySettings, DayOfWeek };