import React from 'react';
import {
  Box,
  Card,
  CardContent,
  Typography,
  Chip,
  Avatar,
  Grid,
  Button,
  Skeleton,
  Alert,
  Pagination,
  FormControl,
  InputLabel,
  Select,
  MenuItem
} from '@mui/material';
import {
  Article as ArticleIcon,
  Event as EventIcon,
  Business as BusinessIcon,
  Folder as FolderIcon,
  Newspaper as NewsIcon,
  Visibility as ViewIcon,
  Edit as EditIcon
} from '@mui/icons-material';
import { format } from 'date-fns';
import { SearchResult, SearchResponse } from '../../types/search';

interface SearchResultsProps {
  results: SearchResponse | null;
  loading: boolean;
  error: string | null;
  onPageChange: (page: number) => void;
  onPageSizeChange: (pageSize: number) => void;
  onViewItem: (item: SearchResult) => void;
  onEditItem?: (item: SearchResult) => void;
  currentPage: number;
  pageSize: number;
}

const getContentTypeIcon = (result: SearchResult) => {
  // Determine content type based on available fields
  if (result.excerpt) return <ArticleIcon />;
  if (result.summary) return <NewsIcon />;
  if ('startDate' in result) return <EventIcon />;
  if ('about' in result) return <BusinessIcon />;
  return <FolderIcon />;
};

const getContentTypeLabel = (result: SearchResult) => {
  if (result.excerpt) return 'Blog Post';
  if (result.summary) return 'News';
  if ('startDate' in result) return 'Event';
  if ('about' in result) return 'Organization';
  return 'Resource';
};

const SearchResultCard: React.FC<{
  result: SearchResult;
  onView: (item: SearchResult) => void;
  onEdit?: (item: SearchResult) => void;
}> = ({ result, onView, onEdit }) => {
  const contentTypeIcon = getContentTypeIcon(result);
  const contentTypeLabel = getContentTypeLabel(result);

  return (
    <Card sx={{ mb: 2, '&:hover': { boxShadow: 4 } }}>
      <CardContent>
        <Box sx={{ display: 'flex', alignItems: 'flex-start', gap: 2 }}>
          <Avatar sx={{ bgcolor: 'primary.main' }}>
            {contentTypeIcon}
          </Avatar>
          
          <Box sx={{ flexGrow: 1 }}>
            {/* Header */}
            <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', mb: 1 }}>
              <Box>
                <Typography variant="h6" component="h3" sx={{ mb: 0.5 }}>
                  <span dangerouslySetInnerHTML={{ __html: result.title || result.name || 'Untitled' }} />
                </Typography>
                <Box sx={{ display: 'flex', gap: 1, alignItems: 'center' }}>
                  <Chip label={contentTypeLabel} size="small" variant="outlined" />
                  {result.status && (
                    <Chip 
                      label={result.status === 1 ? 'Published' : result.status === 2 ? 'Draft' : 'Archived'} 
                      size="small" 
                      color={result.status === 1 ? 'success' : result.status === 2 ? 'warning' : 'default'}
                    />
                  )}
                </Box>
              </Box>
              
              <Box sx={{ display: 'flex', gap: 1 }}>
                <Button
                  size="small"
                  startIcon={<ViewIcon />}
                  onClick={() => onView(result)}
                >
                  View
                </Button>
                {onEdit && (
                  <Button
                    size="small"
                    startIcon={<EditIcon />}
                    onClick={() => onEdit(result)}
                    variant="outlined"
                  >
                    Edit
                  </Button>
                )}
              </Box>
            </Box>

            {/* Content Preview */}
            <Typography variant="body2" color="text.secondary" sx={{ mb: 2 }}>
              <span dangerouslySetInnerHTML={{ 
                __html: result.excerpt || result.summary || result.description || 'No description available' 
              }} />
            </Typography>

            {/* Metadata */}
            <Grid container spacing={2} sx={{ mb: 2 }}>
              {result.organization && (
                <Grid item xs={12} sm={6}>
                  <Typography variant="caption" color="text.secondary">
                    Organization: {result.organization.name}
                  </Typography>
                </Grid>
              )}
              
              {result.country && (
                <Grid item xs={12} sm={6}>
                  <Typography variant="caption" color="text.secondary">
                    Location: {result.city?.name ? `${result.city.name}, ` : ''}{result.country.name}
                  </Typography>
                </Grid>
              )}
              
              {result.createdAt && (
                <Grid item xs={12} sm={6}>
                  <Typography variant="caption" color="text.secondary">
                    Created: {format(new Date(result.createdAt), 'MMM dd, yyyy')}
                  </Typography>
                </Grid>
              )}
              
              {result.modifiedAt && (
                <Grid item xs={12} sm={6}>
                  <Typography variant="caption" color="text.secondary">
                    Modified: {format(new Date(result.modifiedAt), 'MMM dd, yyyy')}
                  </Typography>
                </Grid>
              )}
            </Grid>

            {/* Categories and Tags */}
            <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 0.5 }}>
              {result.categories?.map((category) => (
                <Chip
                  key={category.id}
                  label={category.name}
                  size="small"
                  variant="outlined"
                  color="primary"
                />
              ))}
              {result.tags?.map((tag) => (
                <Chip
                  key={tag.id}
                  label={tag.name}
                  size="small"
                  variant="outlined"
                />
              ))}
            </Box>
          </Box>
        </Box>
      </CardContent>
    </Card>
  );
};

const SearchResultsSkeleton: React.FC = () => (
  <Box>
    {[...Array(5)].map((_, index) => (
      <Card key={index} sx={{ mb: 2 }}>
        <CardContent>
          <Box sx={{ display: 'flex', gap: 2 }}>
            <Skeleton variant="circular" width={40} height={40} />
            <Box sx={{ flexGrow: 1 }}>
              <Skeleton variant="text" width="60%" height={32} />
              <Skeleton variant="text" width="40%" height={20} sx={{ mb: 1 }} />
              <Skeleton variant="text" width="100%" height={20} />
              <Skeleton variant="text" width="80%" height={20} />
              <Box sx={{ display: 'flex', gap: 1, mt: 2 }}>
                <Skeleton variant="rectangular" width={80} height={24} />
                <Skeleton variant="rectangular" width={60} height={24} />
                <Skeleton variant="rectangular" width={70} height={24} />
              </Box>
            </Box>
          </Box>
        </CardContent>
      </Card>
    ))}
  </Box>
);

export const SearchResults: React.FC<SearchResultsProps> = ({
  results,
  loading,
  error,
  onPageChange,
  onPageSizeChange,
  onViewItem,
  onEditItem,
  currentPage,
  pageSize
}) => {
  if (loading) {
    return <SearchResultsSkeleton />;
  }

  if (error) {
    return (
      <Alert severity="error" sx={{ mb: 3 }}>
        {error}
      </Alert>
    );
  }

  if (!results || results.results.length === 0) {
    return (
      <Alert severity="info" sx={{ mb: 3 }}>
        No results found. Try adjusting your search criteria.
      </Alert>
    );
  }

  const totalPages = Math.ceil(results.count / pageSize);

  return (
    <Box>
      {/* Results Summary */}
      <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', mb: 3 }}>
        <Typography variant="body2" color="text.secondary">
          Showing {((currentPage - 1) * pageSize) + 1}-{Math.min(currentPage * pageSize, results.count)} of {results.count} results
        </Typography>
        
        <FormControl size="small" sx={{ minWidth: 120 }}>
          <InputLabel>Per Page</InputLabel>
          <Select
            value={pageSize}
            onChange={(e) => onPageSizeChange(Number(e.target.value))}
            label="Per Page"
          >
            <MenuItem value={10}>10</MenuItem>
            <MenuItem value={20}>20</MenuItem>
            <MenuItem value={50}>50</MenuItem>
            <MenuItem value={100}>100</MenuItem>
          </Select>
        </FormControl>
      </Box>

      {/* Results List */}
      <Box sx={{ mb: 3 }}>
        {results.results.map((result) => (
          <SearchResultCard
            key={result.id}
            result={result}
            onView={onViewItem}
            onEdit={onEditItem}
          />
        ))}
      </Box>

      {/* Pagination */}
      {totalPages > 1 && (
        <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
          <Pagination
            count={totalPages}
            page={currentPage}
            onChange={(_, page) => onPageChange(page)}
            color="primary"
            size="large"
            showFirstButton
            showLastButton
          />
        </Box>
      )}
    </Box>
  );
};