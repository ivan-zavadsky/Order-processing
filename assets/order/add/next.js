export function getNext() {
    const nextItemNumber = 0;
    const selects = document
        .querySelectorAll('select')
    ;
    let selectNumbers = [];
    selects.forEach(function (select) {
        let match = select.id.match(/\d+/)
        selectNumbers.push(
            Number(match[0])
        );
    });
    // console.log(selectNumbers);
    // console.log(Math.max(...selectNumbers));
    return Math.max(...selectNumbers);
}
