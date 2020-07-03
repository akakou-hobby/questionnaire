const analysis = (array) => {
  const results = [];

  array.forEach((data) => {
    const count = array.reduce(
      (p, x) => p + (data.question == x.question && data.answer == x.answer),
      0
    );

    if (count == 0) return;

    array = array.filter(
      (x) => data.question != x.question || data.answer != x.answer
    );

    const result = {
      question: data.question,
      answer: data.answer,
      count: Number(count),
    };

    results.push(result);
  });

  return results;
};
