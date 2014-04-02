<?php global $data; ?>
<?php if($data['header_number'] || $data['header_email']): ?>
<?php echo $data['header_number']; ?><?php if($data['header_number'] && $data['header_email']): ?><span class="sep">|</span><?php endif; ?><a href="mailto:<?php echo $data['header_email']; ?>"><?php echo $data['header_email']; ?></a>
<?php endif; ?>
