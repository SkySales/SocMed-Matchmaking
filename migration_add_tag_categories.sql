-- Add category column to tags table
ALTER TABLE `tags` ADD COLUMN `Category` VARCHAR(100) DEFAULT 'General' AFTER `Tag_Name`;

-- Insert Gamer Type tags
INSERT INTO `tags` (`Tag_Name`, `Category`) VALUES
('Casual', 'Gamer Type'),
('Competitive', 'Gamer Type');

-- Insert Gamer Schedule tags
INSERT INTO `tags` (`Tag_Name`, `Category`) VALUES
('Morning', 'Gaming Schedule'),
('Afternoon', 'Gaming Schedule'),
('Evening', 'Gaming Schedule'),
('Midnight', 'Gaming Schedule');

-- Insert Gaming Frequency tags
INSERT INTO `tags` (`Tag_Name`, `Category`) VALUES
('Daily', 'Gaming Frequency'),
('3-5 times a week', 'Gaming Frequency'),
('1-2 times a week', 'Gaming Frequency');
