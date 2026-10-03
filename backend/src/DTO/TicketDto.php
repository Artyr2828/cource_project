<?php
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
use App\Enums\Priority;

class TicketDto
{
    public function __construct(
        #[Constraints\Count(min: 1)]
        #[Constraints\All([
            new Constraints\NotBlank(message: "Each tag must not be blank"),
            new Constraints\Length(max: 30, maxMessage: "Each tag must not exceed 30 characters")
        ])] 
        public array $adminEmails = [], 

        #[Constraints\Type(type: Priority::class)]
        public ?Priority $priority = null,

        #[Constraints\NotNull(message: "Summary is required")]
        public ?string $summary = null,

        #[Constraints\NotBlank(message: "Link is required")]
        #[Constraints\Url(message: "The URL must be valid", requireTld: false)]
        public ?string $link = null,

        public ?string $position = null,

        public ?string $reportedBy = null,
    ) {}

    public function withReportedBy(string $reportedBy): self
    {
        return new self(
            adminEmails: $this->adminEmails,
            priority: $this->priority,
            summary: $this->summary,
            link: $this->link,
            position: $this->position,
            reportedBy: $reportedBy,
        );
    }
}