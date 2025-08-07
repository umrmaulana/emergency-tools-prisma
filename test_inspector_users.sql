-- Test data untuk inspector users
INSERT INTO `users` (`npk`, `username`, `name`, `password`, `level`) VALUES
('INS001', 'inspector1', 'Inspector One', 'inspector123', 'inspector'),
('INS002', 'inspector2', 'Inspector Two', 'inspector123', 'inspector'),
('12345', 'testinspector', 'Test Inspector', 'test123', 'inspector');

-- Update existing users untuk testing
UPDATE `users` SET `level` = 'inspector' WHERE `npk` = '71030';
