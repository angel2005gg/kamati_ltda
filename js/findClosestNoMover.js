export function findClosestNoMoverRow(currentRow) {
    let closestRow = null;
    const rows = Array.from(currentRow.parentElement.children);

    // Buscar hacia arriba desde la fila actual
    for (let i = rows.indexOf(currentRow) - 1; i >= 0; i--) {
        if (rows[i].classList.contains('no-mover')) {
            closestRow = rows[i];
            break;
        }
    }

    console.log("Fila .no-mover más cercana:", closestRow);
    return closestRow;
}