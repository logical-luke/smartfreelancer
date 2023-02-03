const units = {
  hour: 60 * 60,
  minute: 60,
  second: 1,
};

export function getRelativeTime(startTime, endTime) {
  let elapsed = Math.abs(startTime - endTime);
  let relativeTime = {
    hours: "00",
    minutes: "00",
    seconds: "00",
  };
  if (elapsed < 1) {
    return relativeTime;
  }

  if (elapsed > units["hour"]) {
    const hours = Math.abs(Math.floor(elapsed / units["hour"]));
    relativeTime.hours = hours.toLocaleString("en-US", {
      minimumIntegerDigits: 2,
      useGrouping: false,
    });
    elapsed -= hours * units["hour"];
  }

  if (elapsed > units["minute"]) {
    const minutes = Math.abs(Math.floor(elapsed / units["minute"]));
    relativeTime.minutes = minutes.toLocaleString("en-US", {
      minimumIntegerDigits: 2,
      useGrouping: false,
    });
    elapsed -= minutes * units["minute"];
  }

  const seconds = Math.abs(Math.floor(elapsed / units["second"]));
  relativeTime.seconds = seconds.toLocaleString("en-US", {
    minimumIntegerDigits: 2,
    useGrouping: false,
  });
  elapsed -= seconds * units["second"];

  return relativeTime;
}
