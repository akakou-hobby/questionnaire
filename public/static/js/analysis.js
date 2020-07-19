const compress = (array) => {
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

const analysis = (array) => {
  const compressed = compress(array);
  const results = compressed.sort((a, b) => {
    if (a.question > b.question) {
      return 1;
    } else {
      return -1;
    }
  });

  return results;
};
