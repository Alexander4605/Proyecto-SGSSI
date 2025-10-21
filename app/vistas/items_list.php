<?php
// Este archivo es incluido por index.php, por lo que ya tiene acceso a $conn y a la sesión.

// Definimos el título de la página. La cabecera lo usará.
$title = "Catálogo de Reviews - MovieReviews";

// Incluimos la cabecera reutilizable
include 'partials/header.php';

// Obtener todas las reviews de la base de datos
$sql = "SELECT r.*, u.nombre as autor_nombre FROM reviews r LEFT JOIN usuarios u ON r.user_id = u.id ORDER BY r.id DESC";
$result = mysqli_query($conn, $sql);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<main class="container">
    <div class="catalog-header">
        <h1>Catálogo de Reviews</h1>
        <a href="/add_item" class="button">Añadir Nueva Review</a>
    </div>

    <?php if (empty($reviews)): ?>
        <p class="empty-catalog">Todavía no hay reviews. ¡Sé el primero en añadir una!</p>
    <?php else: ?>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-card-header">
                        <h3><?php echo htmlspecialchars($review['titulo_pelicula']); ?></h3>
                        <span class="review-score"><?php echo htmlspecialchars($review['puntuacion']); ?>/10</span>
                    </div>
                    <div class="review-card-body">
                        <p><strong>Director:</strong> <?php echo htmlspecialchars($review['director']); ?></p>
                        <p><strong>Año:</strong> <?php echo htmlspecialchars($review['ano_estreno']); ?></p>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['texto_review'])); ?></p>
                    </div>
                    <div class="review-card-footer">
                        <small>Publicado por: <?php echo htmlspecialchars($review['autor_nombre'] ?? 'Anónimo'); ?></small>
                        
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                            <div class="review-actions">
                                <a href="/modify_item?item=<?php echo $review['id']; ?>" class="action-button edit">Modificar</a>
                                <a href="/delete_item?item=<?php echo $review['id']; ?>" class="action-button delete">Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>